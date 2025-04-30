<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMS Meeting Session</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- NPC Logo -->
    <link rel="icon" href="logo.png" type="image/png">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.15) 0%, rgba(0, 0, 0, 0.15) 100%), radial-gradient(at top center, rgba(255, 255, 255, 0.40) 0%, rgba(0, 0, 0, 0.40) 120%) #989898;
            background-blend-mode: multiply, multiply;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 91.5vh;
        }

        .attendance-container {
            height: 90%;
            width: 95%;
            border-radius: 20px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .attendance-container>div {
            height: 100%;
            width: 100%;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            padding: 15px;
        }


        .table-container {
            max-height: 90%;
            /* Adjust the height as needed */
            overflow-y: auto;
        }

        .table-container::-webkit-scrollbar {
            width: 10px;
        }

        .table-container::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }

        .table-container::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 5px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        .video-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            /* Adjust as needed */
            margin: 0 auto;
        }

        .corner-square {
            position: absolute;
            width: 30px;
            height: 30px;
            border: 5px solid #fff;
        }

        .top-left {
            top: 10px;
            left: 10px;
            border-right: none;
            border-bottom: none;
        }

        .top-right {
            top: 10px;
            right: 10px;
            border-left: none;
            border-bottom: none;
        }

        .bottom-left {
            bottom: 10px;
            left: 10px;
            border-right: none;
            border-top: none;
        }

        .bottom-right {
            bottom: 10px;
            right: 10px;
            border-left: none;
            border-top: none;
        }

        @keyframes pulse {
            0% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.5;
            }
        }

        .corner-square {
            /* ... existing styles ... */
            animation: pulse 2s infinite;
        }

        .facility-attendees-link {
            color: #343a40 !important;
            /* This is Bootstrap's dark color */
            text-decoration: none;
        }

        .facility-attendees-link:hover {
            color: #1d2124 !important;
            /* A slightly darker shade for hover state */
            text-decoration: none;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_GET['message'])) {
        echo '<div id="autoAlert" class="alert alert-info alert-dismissible fade show" role="alert">
        ' . htmlspecialchars($_GET['message']) . '
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>';

        echo '<script>
        setTimeout(function() {
            $("#autoAlert").alert("close");
        }, 1000);
    </script>';
    }
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand ml-4" href="#">
            <img src="logo.png" alt="NPC Logo" width="30" height="30" class="d-inline-block align-top mr-2">
            Attendance Monitoring System
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./masterlist.php">Masterlist</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#privacyModal">
                        &#128712;
                    </a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0 mr-3">
                <select id="meetingSelect" class="form-control mr-sm-2">
                    <?php
                    include('./conn/conn.php');
                    $stmt = $conn->prepare("SELECT * FROM tbl_meeting_sessions ORDER BY created_at DESC");
                    $stmt->execute();
                    $meetings = $stmt->fetchAll();
                    foreach ($meetings as $meeting) {
                        echo "<option value='{$meeting['id']}'>{$meeting['name']}</option>";
                    }
                    ?>
                </select>
                <button id="newMeetingBtn" class="btn btn-outline-light my-2 my-sm-0" type="button">Create New Meeting</button>
            </form>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="btn btn-outline-light" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main">
        <div class="attendance-container row">
            <div class="qr-container col-4">
                <div class="scanner-con">
                    <h5 class="text-center">Scan your QR Code against the camera</h5>
                    <div class="video-container">
                        <video id="interactive" class="viewport" width="100%"></video>
                        <div class="corner-square top-left"></div>
                        <div class="corner-square top-right"></div>
                        <div class="corner-square bottom-left"></div>
                        <div class="corner-square bottom-right"></div>
                    </div>
                </div>

                <div class="qr-detected-container" style="display: none;">
                    <form id="attendance-form" action="./endpoint/add-attendance.php" method="POST">
                        <h4 class="text-center">Verified QR Detected!</h4>
                        <input type="hidden" id="detected-qr-code" name="qr_code">
                        <input type="hidden" id="signature-data" name="signature_data">
                        <input type="hidden" id="meeting-id" name="meeting_id" value="<?php echo isset($_GET['meeting_id']) ? htmlspecialchars($_GET['meeting_id']) : ''; ?>">
                        <button type="button" class="btn btn-dark form-control" data-toggle="modal" data-target="#signatureModal">Sign and Submit</button>
                        <p></p>
                        <button type="button" class="btn btn-danger" onClick="window.location.reload();">Cancel</button>
                    </form>
                </div>
            </div>

            <div class="attendance-list col-8">
                <h4>List of Attendees</h4>
                <div class="table-container table-responsive">
                    <table class="table text-center table-sm" id="attendanceTable">
                        <thead class="thead-dark">
                            <tr id="headerRow">
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col" style="display: none;">Age</th>
                                <th scope="col" style="display: none;">Gender</th>
                                <th scope="col" style="display: none;">Email</th>
                                <th scope="col" style="display: none;">Phone</th>
                                <th scope="col">Station</th>
                                <th scope="col">Position</th>
                                <th scope="col">Time In</th>
                                <th scope="col">Time Out</th>
                                <th scope="col" style="display: none;">Signature</th>
                                <th scope="col">Remarks</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include('./conn/conn.php');
                            $stmt = $conn->prepare("SELECT * FROM tbl_attendance LEFT JOIN tbl_student ON tbl_student.tbl_student_id = tbl_attendance.tbl_student_id");
                            $stmt->execute();

                            $result = $stmt->fetchAll();
                            $rowNumber = 1; // Initialize the row number

                            foreach ($result as $row) {
                                $attendanceID = $row["tbl_attendance_id"];
                                $studentName = $row["student_name"];
                                $studentAge = $row["student_age"];
                                $studentGender = $row["student_gender"];
                                $studentEmail = $row["student_email"];
                                $studentPhone = $row["student_phone"];
                                $studentCourse = $row["course_section"];
                                $studentPosition = $row["student_position"];
                                $timeIn = $row["time_in"];
                                $timeOut = $row["time_out"];
                            ?>

                                <tr>
                                    <th scope="row"><?= $rowNumber ?></th>
                                    <td><?= $studentName ?></td>
                                    <td style="display: none;"><?= $studentAge ?></td>
                                    <td style="display: none;"><?= $studentGender ?></td>
                                    <td style="display: none;"><?= $studentEmail ?></td>
                                    <td style="display: none;"><?= $studentPhone ?></td>
                                    <td><?= $studentCourse ?></td>
                                    <td><?= $studentPosition ?></td>
                                    <td><?= $timeIn ?></td>
                                    <td><?= $timeOut ? $timeOut : '-' ?></td>
                                    <td style="display: none;" data-signature="<?= htmlspecialchars($row['signature_data']) ?>"></td>
                                    <td><?= htmlspecialchars($row['remarks'] ?? '-') ?></td>
                                    <td>
                                        <div class="action-button">
                                            <button class="btn btn-secondary btn-sm add-remarks-button" data-id="<?= $attendanceID ?>" data-toggle="modal" data-target="#remarksModal">&#128393;</button>
                                            <button class="btn btn-danger btn-sm delete-button" onclick="deleteAttendance(<?= $attendanceID ?>)">&#10006;</button>
                                        </div>
                                    </td>
                                </tr>

                            <?php
                                $rowNumber++;
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="facility-attendees-list mt-4">
                        <h4>
                            <a class="facility-attendees-link" data-toggle="collapse" href="#facilityAttendeesCollapse" role="btn-dark" aria-expanded="false" aria-controls="facilityAttendeesCollapse">
                                List of Facilitators <i class="fas fa-chevron-down"></i>
                            </a>
                        </h4>
                        <div class="collapse" id="facilityAttendeesCollapse">
                            <div class="table-container table-responsive">
                                <table class="table text-center table-sm" id="facilityAttendeesTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Facility attendees will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                    </div>
                </div>
                <button class="btn btn-dark" onclick="printPDF()">Print as PDF</button>
                <button class="btn btn-dark" onclick="printPDFQMS()">Print as PDF(QMS)</button>
                <button class="btn btn-primary" data-toggle="modal" data-target="#facilityAttendeeModal">Add Facilitator</button>
                <button class="btn btn-danger" onclick="deleteMeetingSession()">Delete Meeting Session</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <!-- instascan JS -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <!-- jsPDF library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- jsPDF autoTable plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <!-- Signature Pad JS -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        let scanner;
        let signaturePad;

        function startScanner() {
            scanner = new Instascan.Scanner({
                video: document.getElementById('interactive')
            });

            scanner.addListener('scan', function(content) {
                $("#detected-qr-code").val(content);
                console.log(content);
                scanner.stop();
                document.querySelector(".qr-detected-container").style.display = '';
                document.querySelector(".scanner-con").style.display = 'none';

                // Show signature modal
                $('#signatureModal').modal('show');
            });

            Instascan.Camera.getCameras()
                .then(function(cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]);
                    } else {
                        console.error('No cameras found.');
                        alert('No cameras found.');
                    }
                })
                .catch(function(err) {
                    console.error('Camera access error:', err);
                    alert('Camera access error: ' + err);
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            startScanner();

            // Initialize signature pad
            const canvas = document.getElementById('signatureCanvas');
            signaturePad = new SignaturePad(canvas);
            // Clear signature
            document.getElementById('clearSignature').addEventListener('click', function() {
                signaturePad.clear();
            });

            // Save signature and submit form
            document.getElementById('saveSignature').addEventListener('click', function() {
                if (signaturePad.isEmpty()) {
                    alert('Please provide a signature first.');
                    return;
                }

                const signatureData = signaturePad.toDataURL();
                document.getElementById('signature-data').value = signatureData;
                document.getElementById('meeting-id').value = $('#meetingSelect').val();
                document.getElementById('attendance-form').submit();
            });
        });

        function deleteAttendance(id) {
            if (confirm("Do you want to remove this attendance?")) {
                $.ajax({
                    url: "./endpoint/delete-attendance.php?attendance=" + id,
                    method: 'GET',
                    success: function(response) {
                        // Reload the attendance for the current meeting
                        loadAttendance($('#meetingSelect').val());
                    },
                    error: function() {
                        alert('An error occurred while deleting the attendance. Please try again.');
                    }
                });
            }
        }

        function deleteMeetingSession() {
            const sessionId = $('#meetingSelect').val();
            if (!sessionId) {
                alert("No meeting session selected.");
                return;
            }

            if (confirm("Are you sure you want to delete the entire meeting session? This will remove all current attendees.")) {
                $.ajax({
                    url: './endpoint/delete-meeting-session.php',
                    method: 'POST',
                    data: {
                        sessionId: sessionId
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            alert(data.message);
                            // Remove the option from the select element
                            $(`#meetingSelect option[value="${sessionId}"]`).remove();
                            // Clear the attendance table
                            $('#attendanceTable tbody').empty();
                            // Select the first available meeting, if any
                            if ($('#meetingSelect option').length > 0) {
                                $('#meetingSelect').val($('#meetingSelect option:first').val()).trigger('change');
                            }
                        } else {
                            alert("Failed to delete meeting session: " + (data.error || data.message));
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error:', textStatus, errorThrown);
                        alert("An error occurred. Please try again.");
                    }
                });
            }
        }

        const {
            jsPDF
        } = window.jspdf;

        async function printPDF() {
            const selectedFields = await promptForFieldsSelection();
            const meetingId = $('#meetingSelect').val();

            // Fetch meeting details
            const meetingDetails = await getMeetingDetails(meetingId);

            const doc = new jsPDF();

            // Helper function to draw header
            function drawHeader(doc) {
                doc.addImage('logo.png', 'PNG', doc.internal.pageSize.width - 115, 6, 20, 20);
                doc.setFontSize(10);
                doc.text("National Power Corporation", 85, 30);
                doc.setFontSize(16);
                doc.setFont(undefined, 'bold');
                doc.text("ATTENDANCE SHEET", 76, 35);
                doc.setFont(undefined, 'normal');
            }

            // Draw initial header and meeting info
            drawHeader(doc);
            drawMeetingInfo(doc, meetingDetails);

            // Get table data
            const table = document.getElementById("attendanceTable");
            const headers = [];
            const data = [];

            // Get headers
            for (let i = 0; i < table.rows[0].cells.length; i++) {
                if (selectedFields[i]) {
                    headers.push(table.rows[0].cells[i].textContent.trim());
                }
            }

            // Get data
            for (let i = 1; i < table.rows.length; i++) {
                const row = table.rows[i];
                const rowData = [];
                for (let j = 0; j < row.cells.length; j++) {
                    if (selectedFields[j]) {
                        if (j === 10) { // Signature column
                            const signatureData = row.cells[j].getAttribute('data-signature');
                            rowData.push(signatureData && signatureData.startsWith('data:image') ? {
                                content: '',
                                image: signatureData
                            } : 'No valid signature');
                        } else {
                            rowData.push(row.cells[j].textContent.trim());
                        }
                    }
                }
                data.push(rowData);
            }

            // Table styles
            const tableStyles = {
                headStyles: {
                    halign: 'center',
                    font: 'helvetica',
                    lineColor: [0, 0, 0],
                    lineWidth: 0.5,
                    fillColor: [255, 255, 255],
                    textColor: [0, 0, 0],
                    fontStyle: 'bold',
                },
                bodyStyles: {
                    fontSize: 8,
                    halign: 'center',
                    font: 'helvetica',
                    lineColor: [0, 0, 0],
                    textColor: [0, 0, 0],
                },
                theme: 'grid',
            };

            // Generate attendees table
            let finalY = doc.autoTable({
                head: [headers],
                body: data,
                startY: 78,
                ...tableStyles,
                didDrawCell: function(data) {
                    if (data.column.index === headers.indexOf('Signature') && data.cell.raw && data.cell.raw.image) {
                        try {
                            const cellWidth = data.cell.width - 2;
                            const cellHeight = data.cell.height - 2;
                            const aspectRatio = 2;
                            let imgWidth = Math.min(cellWidth, cellHeight * aspectRatio);
                            let imgHeight = imgWidth / aspectRatio;
                            const xPos = data.cell.x + (cellWidth - imgWidth) / 2;
                            const yPos = data.cell.y + (cellHeight - imgHeight) / 2;
                            doc.addImage(data.cell.raw.image, 'PNG', xPos, yPos, imgWidth, imgHeight);
                        } catch (error) {
                            console.error('Error adding signature image:', error);
                        }
                    }
                },
                didDrawPage: function(data) {
                    drawHeader(doc);
                    if (data.pageNumber > 1) {
                        data.settings.startY = 45;
                    }
                    drawFooter(doc);
                },
                margin: {
                    top: 45
                },
            }).lastAutoTable.finalY;

            // Add facility attendees table
            const facilityAttendees = await getFacilityAttendeesForPDF(meetingId);
            if (facilityAttendees.length > 0) {
                doc.setFontSize(12);
                doc.setFont(undefined, 'bold');
                doc.text("List of Facilitators", 14, finalY + 15);
                doc.setFont(undefined, 'normal');

                const facilityAttendeeHeaders = ['#', 'Name', 'Role', 'Signature'];
                const facilityAttendeeData = facilityAttendees.map((attendee, index) => [
                    index + 1,
                    attendee.name,
                    attendee.role,
                    {
                        content: '',
                        image: attendee.signature_data
                    }
                ]);

                doc.autoTable({
                    startY: finalY + 18,
                    head: [facilityAttendeeHeaders],
                    body: facilityAttendeeData,
                    ...tableStyles,
                    didDrawCell: function(data) {
                        if (data.column.index === 3 && data.cell.section === 'body') {
                            const cellHeight = data.cell.height - 2;
                            const cellWidth = data.cell.width - 2;
                            const aspectRatio = 2;
                            let imgWidth = Math.min(cellWidth, cellHeight * aspectRatio);
                            let imgHeight = imgWidth / aspectRatio;
                            const xPos = data.cell.x + (cellWidth - imgWidth) / 2;
                            const yPos = data.cell.y + (cellHeight - imgHeight) / 2;
                            const img = data.row.raw[3].image;
                            if (img) {
                                doc.addImage(img, 'PNG', xPos, yPos, imgWidth, imgHeight);
                            }
                        }
                    },
                    didDrawPage: function(data) {
                        drawHeader(doc);
                        drawFooter(doc);
                    },
                });
            }

            // Save the PDF
            doc.save(`${meetingDetails.name.replace(/[^a-z0-9]/gi, '_').toLowerCase()}-attendance-sheet.pdf`);
        }

        function drawFooter(doc) {
            doc.setFontSize(7);
            let y = doc.internal.pageSize.height - 7;
            doc.text('NPC-005.F02', 14, y - 6);
            doc.text('Rev No. 1', 14, y - 3);
            let str = 'Sheet ' + doc.internal.getNumberOfPages();
            doc.text(str, 14, y);
        }

        function drawMeetingInfo(doc, meetingDetails) {
            doc.setFontSize(12);
            doc.text("Title of Meeting/Activity:", 14, 45);
            doc.setFont(undefined, 'bold');
            doc.text(meetingDetails.name, 60, 45);

            doc.setFont(undefined, 'normal');
            doc.text("Date:", 14, 50);
            doc.setFont(undefined, 'bold');
            if (meetingDetails.date_start === meetingDetails.date_end) {
                doc.text(meetingDetails.date_start, 25, 50);
            } else {
                doc.text(`${meetingDetails.date_start} to ${meetingDetails.date_end}`, 25, 50);
            }

            doc.setFont(undefined, 'normal');
            doc.text("Time:", 14, 55);
            doc.setFont(undefined, 'bold');
            doc.text(`${meetingDetails.time_start} - ${meetingDetails.time_end}`, 25, 55);

            doc.setFont(undefined, 'normal');
            doc.text("Venue:", 14, 60);
            doc.setFont(undefined, 'bold');
            doc.text(meetingDetails.venue, 28, 60);

            doc.setFont(undefined, 'normal');
            doc.text("Purpose:", 14, 65);
            doc.setFont(undefined, 'bold');
            doc.text(meetingDetails.purpose, 32, 65);

            doc.setFont(undefined, 'bold');
            doc.text("List of Participants", 14, 75);
            doc.setFont(undefined, 'normal');
        }

        async function getFacilityAttendeesForPDF(meetingId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: './endpoint/get-facility-attendees.php',
                    method: 'GET',
                    data: {
                        meetingId: meetingId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            resolve(response.attendees);
                        } else {
                            reject(response.error);
                        }
                    },
                    error: function() {
                        reject('An error occurred while loading facility attendees.');
                    }
                });
            });
        }

        async function promptForFieldsSelection() {
            return new Promise(resolve => {
                const fields = [{
                        label: '#',
                        checked: true
                    },
                    {
                        label: 'Name',
                        checked: true
                    },
                    {
                        label: 'Age',
                        checked: false
                    },
                    {
                        label: 'Gender',
                        checked: false
                    },
                    {
                        label: 'Email',
                        checked: false
                    },
                    {
                        label: 'Phone',
                        checked: false
                    },
                    {
                        label: 'Station',
                        checked: true
                    },
                    {
                        label: 'Position',
                        checked: true
                    },
                    {
                        label: 'Time In',
                        checked: true
                    },
                    {
                        label: 'Time Out',
                        checked: true
                    },
                    {
                        label: 'Signature',
                        checked: true
                    },
                    {
                        label: 'Remarks',
                        checked: true
                    }
                ];

                const modalBody = document.createElement('div');
                modalBody.className = 'modal-body';

                const fieldSelectionDiv = document.createElement('div');
                fieldSelectionDiv.innerHTML = '<h5>Select participant fields to include:</h5>';
                modalBody.appendChild(fieldSelectionDiv);

                fields.forEach(field => {
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    div.innerHTML = `
                <input class="form-check-input" type="checkbox" id="field-${field.label}" ${field.checked ? 'checked' : ''}>
                <label class="form-check-label" for="field-${field.label}">
                    ${field.label}
                </label>
            `;
                    modalBody.appendChild(div);
                });

                const modalFooter = document.createElement('div');
                modalFooter.className = 'modal-footer';
                const confirmButton = document.createElement('button');
                confirmButton.className = 'btn btn-dark';
                confirmButton.textContent = 'Confirm';
                confirmButton.addEventListener('click', () => {
                    const selectedFields = fields.map(field => document.getElementById(`field-${field.label}`).checked);
                    resolve(selectedFields);
                    $(modal).modal('hide');
                });
                modalFooter.appendChild(confirmButton);

                const closeButton = document.createElement('button');
                closeButton.className = 'btn btn-secondary';
                closeButton.textContent = 'Close';
                closeButton.addEventListener('click', () => {
                    $(modal).modal('hide');
                });
                modalFooter.appendChild(closeButton);

                const modalContent = document.createElement('div');
                modalContent.className = 'modal-content';
                modalContent.appendChild(modalBody);
                modalContent.appendChild(modalFooter);

                const modalDialog = document.createElement('div');
                modalDialog.className = 'modal-dialog';
                modalDialog.appendChild(modalContent);

                const modal = document.createElement('div');
                modal.className = 'modal fade';
                modal.tabIndex = -1;
                modal.setAttribute('role', 'dialog');
                modal.appendChild(modalDialog);

                document.body.appendChild(modal);

                $(modal).modal('show');

                $(modal).on('hidden.bs.modal', function() {
                    $(modal).remove();
                });
            });
        }

        async function getMeetingDetails(meetingId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: './endpoint/get-meeting-details.php',
                    method: 'GET',
                    data: {
                        meetingId: meetingId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            resolve(response.meeting);
                        } else {
                            reject('Failed to fetch meeting details');
                        }
                    },
                    error: function() {
                        reject('An error occurred while fetching meeting details');
                    }
                });
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.add-remarks-button', function() {
                var attendanceId = $(this).data('id');
                var meetingId = $('#meetingSelect').val();
                console.log('Opening modal with:');
                console.log('Attendance ID:', attendanceId);
                console.log('Meeting ID:', meetingId);
                $('#attendanceId').val(attendanceId);
                $('#meetingId').val(meetingId);
            });
            $('#saveRemarks').click(function() {
                var attendanceId = $('#attendanceId').val();
                var remarks = $('#remarks').val();
                var meetingId = $('#meetingId').val();

                console.log('About to send AJAX request with:');
                console.log('Attendance ID:', attendanceId);
                console.log('Remarks:', remarks);
                console.log('Meeting ID:', meetingId);

                if (!attendanceId || !meetingId) {
                    alert('Error: Missing attendance ID or meeting ID');
                    return;
                }

                $.ajax({
                    url: './endpoint/add-remarks.php',
                    method: 'POST',
                    data: {
                        attendanceId: attendanceId,
                        remarks: remarks,
                        meetingId: meetingId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#remarksModal').modal('hide');
                            loadAttendance(meetingId);
                        } else {
                            alert('Failed to add remarks: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Function to get URL parameters
            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            };

            // Check if there's a meeting_id in the URL
            var meetingIdFromUrl = getUrlParameter('meeting_id');
            if (meetingIdFromUrl) {
                $('#meetingSelect').val(meetingIdFromUrl);
            }

            // Load attendance for the selected meeting
            $('#meetingSelect').change(function() {
                loadAttendance($(this).val());
            }).trigger('change'); // Trigger change event to load initial attendance

            // Create new meeting
            $('#newMeetingBtn').click(function() {
                $('#createMeetingModal').modal('show');
            });

            $('#saveMeeting').click(function() {
                const meetingName = $('#meetingName').val();
                const dateStart = $('#meetingDateStart').val();
                const dateEnd = $('#meetingDateEnd').val();
                const timeStart = $('#meetingTimeStart').val();
                const timeEnd = $('#meetingTimeEnd').val();
                const venue = $('#meetingVenue').val();
                const purpose = $('#meetingPurpose').val();

                if (meetingName && dateStart && dateEnd && timeStart && timeEnd && venue && purpose) {
                    $.ajax({
                        url: './endpoint/create-meeting.php',
                        method: 'POST',
                        data: {
                            name: meetingName,
                            date_start: dateStart,
                            date_end: dateEnd,
                            time_start: timeStart,
                            time_end: timeEnd,
                            venue: venue,
                            purpose: purpose
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#meetingSelect').append($('<option>', {
                                    value: response.id,
                                    text: meetingName
                                }));
                                $('#meetingSelect').val(response.id).trigger('change');
                                $('#createMeetingModal').modal('hide');
                                // Clear form
                                $('#createMeetingForm')[0].reset();
                            } else {
                                alert('Failed to create meeting. Please try again.');
                            }
                        },
                        error: function() {
                            alert('An error occurred. Please try again.');
                        }
                    });
                } else {
                    alert('Please fill all fields.');
                }
            });

            // Load initial attendance
            loadAttendance($('#meetingSelect').val());
        });

        function loadAttendance(meetingId) {
            $.ajax({
                url: './endpoint/get-attendance.php',
                method: 'GET',
                data: {
                    meetingId: meetingId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Clear existing table rows
                        $('#attendanceTable tbody').empty();

                        // Add new rows
                        response.attendance.forEach(function(row, index) {
                            $('#attendanceTable tbody').append(`
                        <tr>
                            <th scope="row">${index + 1}</th>
                            <td>${row.student_name}</td>
                            <td style="display: none;">${row.student_age}</td>
                            <td style="display: none;">${row.student_gender}</td>
                            <td style="display: none;">${row.student_email}</td>
                            <td style="display: none;">${row.student_phone}</td>
                            <td>${row.course_section}</td>
                            <td>${row.student_position}</td>
                            <td>${row.time_in}</td>
                            <td>${row.time_out || '-'}</td>
                            <td style="display: none;" data-signature="${row.signature_data}"></td>
                            <td>${row.remarks || '-'}</td>
                            <td>
                                <div class="action-button">
                                    <button class="btn btn-secondary btn-sm add-remarks-button" data-id="${row.tbl_attendance_id}" data-toggle="modal" data-target="#remarksModal">&#128393;</button>
                                    <button class="btn btn-danger btn-sm delete-button" onclick="deleteAttendance(${row.tbl_attendance_id})">&#10006;</button>
                                </div>
                            </td>
                        </tr>
                    `);
                        });

                        // Update meeting details
                        if (response.meeting) {
                            $('#meetingName').text(response.meeting.name);
                            $('#meetingDate').text(`${response.meeting.date_start} to ${response.meeting.date_end}`);
                            $('#meetingTime').text(`${response.meeting.time_start} - ${response.meeting.time_end}`);
                            $('#meetingVenue').text(response.meeting.venue);
                            $('#meetingPurpose').text(response.meeting.purpose);
                        }
                    } else {
                        alert('Failed to load attendance. Please try again.');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }

        let facilityAttendees = [];
        let attendeeSignaturePad;

        $(document).ready(function() {
            // Initialize signature pad for facility attendees
            const attendeeCanvas = document.getElementById('attendeeSignatureCanvas');
            attendeeSignaturePad = new SignaturePad(attendeeCanvas);

            // Clear attendee signature
            $('#clearAttendeeSignature').click(function() {
                attendeeSignaturePad.clear();
            });

            // Save facility attendee
            $('#saveFacilityAttendee').click(function() {
                const name = $('#attendeeName').val();
                const role = $('#attendeeRole').val();
                const signature = attendeeSignaturePad.isEmpty() ? null : attendeeSignaturePad.toDataURL();
                const meetingId = $('#meetingSelect').val();

                if (name && role && signature && meetingId) {
                    $.ajax({
                        url: './endpoint/add-facility-attendee.php',
                        method: 'POST',
                        data: {
                            meetingId: meetingId,
                            name: name,
                            role: role,
                            signatureData: signature
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#facilityAttendeeModal').modal('hide');
                                alert('Facility attendee added successfully!');
                                loadFacilityAttendees(meetingId);

                                // Clear form
                                $('#attendeeName').val('');
                                $('#attendeeRole').val('Resource speaker');
                                attendeeSignaturePad.clear();
                            } else {
                                alert('Failed to add facility attendee: ' + response.error);
                            }
                        },
                        error: function() {
                            alert('An error occurred. Please try again.');
                        }
                    });
                } else {
                    alert('Please fill all fields and provide a signature.');
                }
            });

            // Load facility attendees when meeting is changed
            $('#meetingSelect').change(function() {
                loadFacilityAttendees($(this).val());
            });

            // Initial load of facility attendees
            loadFacilityAttendees($('#meetingSelect').val());

            // Toggle chevron icon
            $('[data-toggle="collapse"]').on('click', function() {
                $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
            });
        });

        function loadFacilityAttendees(meetingId) {
            $.ajax({
                url: './endpoint/get-facility-attendees.php',
                method: 'GET',
                data: {
                    meetingId: meetingId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const attendees = response.attendees;
                        const tbody = $('#facilityAttendeesTable tbody');
                        tbody.empty();

                        if (attendees.length > 0) {
                            attendees.forEach((attendee, index) => {
                                tbody.append(`
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>${attendee.name}</td>
                                <td>${attendee.role}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-facility-attendee" data-id="${attendee.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                            });
                        }

                        // Add delete functionality
                        $('.delete-facility-attendee').click(function() {
                            const attendeeId = $(this).data('id');
                            deleteFacilityAttendee(attendeeId, meetingId);
                        });
                    } else {
                        alert('Failed to load facility attendees: ' + response.error);
                    }
                },
                error: function() {
                    alert('An error occurred while loading facility attendees. Please try again.');
                }
            });
        }

        function deleteFacilityAttendee(attendeeId, meetingId) {
            if (confirm('Are you sure you want to delete this attendee?')) {
                $.ajax({
                    url: './endpoint/delete-facility-attendee.php',
                    method: 'POST',
                    data: {
                        attendeeId: attendeeId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Attendee deleted successfully!');
                            loadFacilityAttendees(meetingId);
                        } else {
                            alert('Failed to delete attendee: ' + response.error);
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting attendee. Please try again.');
                    }
                });
            }
        }
    </script>
    <script>
        async function printPDFQMS() {
            const meetingId = $('#meetingSelect').val();

            // Fetch meeting details
            const meetingDetails = await getMeetingDetails(meetingId);

            const doc = new jsPDF();

            // Helper function to draw header
            function drawHeader(doc) {
                doc.addImage('logo.png', 'PNG', doc.internal.pageSize.width - 115, 6, 20, 20);
                doc.setFontSize(10);
                doc.text("National Power Corporation", 85, 30);
                doc.setFontSize(16);
                doc.setFont(undefined, 'bold');
                doc.text("ATTENDANCE SHEET", 76, 35);
                doc.setFont(undefined, 'normal');
            }

            // Draw initial header and meeting info
            drawHeader(doc);
            drawMeetingInfo(doc, meetingDetails);

            // Get table data
            const table = document.getElementById("attendanceTable");
            const headers = ['#', 'Name', 'Station', 'Position', 'Signature', 'Remarks'];
            const data = [];

            // Get data
            for (let i = 1; i < table.rows.length; i++) {
                const row = table.rows[i];
                const rowData = [
                    row.cells[0].textContent.trim(), // #
                    row.cells[1].textContent.trim(), // Name
                    row.cells[6].textContent.trim(), // Station
                    row.cells[7].textContent.trim(), // Position
                    '', // Signature (leave empty, we'll add the image later)
                    row.cells[11].textContent.trim() // Remarks
                ];
                data.push(rowData);
            }

            // Table styles
            const tableStyles = {
                headStyles: {
                    halign: 'center',
                    font: 'helvetica',
                    lineColor: [0, 0, 0],
                    lineWidth: 0.5,
                    fillColor: [255, 255, 255],
                    textColor: [0, 0, 0],
                    fontStyle: 'bold',
                },
                bodyStyles: {
                    fontSize: 8,
                    halign: 'center',
                    font: 'helvetica',
                    lineColor: [0, 0, 0],
                    textColor: [0, 0, 0],
                },
                theme: 'grid',
            };

            // Generate attendees table
            doc.autoTable({
                head: [headers],
                body: data,
                startY: 78,
                ...tableStyles,
                didDrawCell: function(data) {
                    if (data.column.index === 4 && data.cell.section === 'body') {
                        const cellHeight = data.cell.height - 2;
                        const cellWidth = data.cell.width - 2;
                        const aspectRatio = 2;
                        let imgWidth = Math.min(cellWidth, cellHeight * aspectRatio);
                        let imgHeight = imgWidth / aspectRatio;
                        const xPos = data.cell.x + (cellWidth - imgWidth) / 2;
                        const yPos = data.cell.y + (cellHeight - imgHeight) / 2;
                        const img = table.rows[data.row.index + 1].cells[10].getAttribute('data-signature');
                        if (img && img.startsWith('data:image')) {
                            doc.addImage(img, 'PNG', xPos, yPos, imgWidth, imgHeight);
                        }
                    }
                },
                didDrawPage: function(data) {
                    drawHeader(doc);
                    if (data.pageNumber > 1) {
                        data.settings.startY = 45;
                    }
                    drawFooter(doc);
                },
                margin: {
                    top: 45
                },
            });

            // Save the PDF
            doc.save(`${meetingDetails.name.replace(/[^a-z0-9]/gi, '_').toLowerCase()}-attendance-sheet-qms.pdf`);
        }
    </script>
    </script>
    <!-- Signature Modal -->
    <div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signatureModalLabel">Please sign here</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <canvas id="signatureCanvas" width="400" height="200" style="border: 1px solid #000;"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="clearSignature">Clear</button>
                    <button type="button" class="btn btn-primary" id="saveSignature">Save</button>
                    <button type="button" class="btn btn-danger" onClick="window.location.reload();">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" role="dialog" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">&#128712; Data Privacy Statement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="width: 90%; margin: 0 auto;">
                        <h5><strong>HOW THE NATIONAL POWER CORPORATION USES PERSONAL INFORMATION</strong>
                        </h5>
                        <p>NPC uses the personal information gathered from its employees, customers and stakeholders in the course of
                            its official business primarily for managing and administering corporate procedures and functions in relation
                            to its statutory mandates of Missionary Electrification, Watershed and Dams Management and Operation and
                            Maintenance of undisposed Generation Assets the scope of which covers offices spread over the entire
                            country. </p>

                        <p>Information is generally gathered through forms which are filled up in relation to procedures arising out of
                            various corporate functions such as hiring and personnel development, procurement activities, contract
                            administration, security management, among others. Information retained by the Corporation is either
                            manually recorded in logbooks or stored in data bases which are secured and handled under strict guidelines
                            for access.</p>

                        <p>No person other than NPC, its employees, government agencies and their authorized representatives are
                            allowed to access retained personal information in NPC’s database or under its physical possession subject to
                            the provisions of Executive Order No. 2, s. 2016 (Operationalizing in the Executive Branch the People’s
                            Constitutional Right to Information and the State Policies to Full Public Disclosure and Transparency in the Public
                            Service and Providing Guidelines therefore). </p>

                        <h5>
                            <strong>DATA QUALITY AND SECURITY</strong>
                        </h5>
                        <p>
                            NPC is committed to taking all reasonable steps to make sure the information acquired using manual form, and
                            digital information collected and held on its database, are accurate and secure.
                        </p>
                        <h5>
                            <strong>ACCESS AND UPDATING CUSTOMER INFORMATION</strong>
                        </h5>
                        <p>
                            Review and updating of information held by NPC may be done by sending a formal request to the Data Privacy
                            Officer (DPO) or his authorized representatives in NPC’s offices nationwide. The request may also be emailed
                            to the DPO at dpo@napocor.gov.ph.
                        </p>
                        <h5>
                            <strong> HOW TO CONTACT US</strong>
                        </h5>
                        <p>
                            Queries, clarifications or requests on any aspect of this Data Privacy Statement, the exercise of rights pertaining
                            to personal information, or for any feedback about NPC’s processing of personal information can be coursed
                            through our Corporate Hotline at 924 5300 / 921 3541 to 48 or via email to dpo@napocor.gov.ph.
                            NPC’s DPO can be reached at:
                            <br><br>
                            <strong>DATA PRIVACY OFFICER</strong><br>
                            National Power Corporation<br>
                            NPC Building, Quezon Avenue corner BIR Road<br>
                            Diliman, Quezon City<br>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Remarks Modal -->
    <div class="modal fade" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="remarksModalLabel">Add Remarks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="remarksForm">
                        <input type="hidden" id="attendanceId" name="attendanceId">
                        <input type="hidden" id="meetingId" name="meetingId">
                        <div class="form-group">
                            <label for="remarks">Remarks:</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveRemarks">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Facility Attendee Modal -->
    <div class="modal fade" id="facilityAttendeeModal" tabindex="-1" role="dialog" aria-labelledby="facilityAttendeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="facilityAttendeeModalLabel">Add Facilitator</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="facilityAttendeeForm">
                        <div class="form-group">
                            <label for="attendeeName">Name:</label>
                            <input type="text" class="form-control" id="attendeeName" required>
                        </div>
                        <div class="form-group">
                            <label for="attendeeRole">Role:</label>
                            <select class="form-control" id="attendeeRole" required>
                                <option value="Resource speaker">Resource speaker</option>
                                <option value="IS/IT personnel">IS/IT personnel</option>
                                <option value="HR personnel">HR personnel</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Signature:</label>
                            <canvas id="attendeeSignatureCanvas" width="400" height="200" style="border: 1px solid #000;"></canvas>
                            <button type="button" class="btn btn-secondary btn-sm mt-2" id="clearAttendeeSignature">Clear Signature</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveFacilityAttendee">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Meeting Modal -->
    <div class="modal fade" id="createMeetingModal" tabindex="-1" role="dialog" aria-labelledby="createMeetingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMeetingModalLabel">Create New Meeting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createMeetingForm">
                        <div class="form-group">
                            <label for="meetingName">Title of Meeting/Activity:</label>
                            <input type="text" class="form-control" id="meetingName" required>
                        </div>
                        <div class="form-group">
                            <label for="meetingDateStart">Start Date:</label>
                            <input type="date" class="form-control" id="meetingDateStart" required>
                        </div>
                        <div class="form-group">
                            <label for="meetingDateEnd">End Date:</label>
                            <input type="date" class="form-control" id="meetingDateEnd">
                        </div>
                        <div class="form-group">
                            <label for="meetingTimeStart">Start Time:</label>
                            <input type="time" class="form-control" id="meetingTimeStart" required>
                        </div>
                        <div class="form-group">
                            <label for="meetingTimeEnd">End Time:</label>
                            <input type="time" class="form-control" id="meetingTimeEnd">
                        </div>
                        <div class="form-group">
                            <label for="meetingVenue">Venue:</label>
                            <input type="text" class="form-control" id="meetingVenue" required>
                        </div>
                        <div class="form-group">
                            <label for="meetingPurpose">Purpose:</label>
                            <textarea class="form-control" id="meetingPurpose" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveMeeting">Save Meeting</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>