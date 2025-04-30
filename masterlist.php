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
    <title>AMS Masterlist</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

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

        .student-container {
            height: 98%;
            width: 98%;
            border-radius: 20px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .student-container>div {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            padding: 20px;
            height: 100%;
        }

        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-container {
            height: 90%;
            width: 100%;
            /* Adjust this value as needed */
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


        table.dataTable thead>tr>th.sorting,
        table.dataTable thead>tr>th.sorting_asc,
        table.dataTable thead>tr>th.sorting_desc,
        table.dataTable thead>tr>th.sorting_asc_disabled,
        table.dataTable thead>tr>th.sorting_desc_disabled,
        table.dataTable thead>tr>td.sorting,
        table.dataTable thead>tr>td.sorting_asc,
        table.dataTable thead>tr>td.sorting_desc,
        table.dataTable thead>tr>td.sorting_asc_disabled,
        table.dataTable thead>tr>td.sorting_desc_disabled {
            text-align: center;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand ml-4" href="#">
            <img src="logo.png" alt="NPC Logo" width="30" height="30" class="d-inline-block align-top mr-2">
            Attendance Monitoring System
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="./masterlist.php">Masterlist</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#privacyModal">
                        &#128712;
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mr-3">
                    <a class="btn btn-outline-light" href="register.php">Register User</a>
                </li>
                <li class="nav-item mr-3">
                    <a class="btn btn-outline-light" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="main">
        <div class="student-container">
            <div class="student-list">
                <div class="title">
                    <h4>List of Employees</h4>
                    <button class="btn btn-dark" data-toggle="modal" data-target="#addStudentModal">Add Member</button>
                </div>
                <hr>
                <div class="table-container table-responsive">
                    <table class="table text-center table-sm" id="studentTable">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Age</th>
                                <th scope="col">Gender</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Station</th>
                                <th scope="col">Position</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            include('./conn/conn.php');

                            $stmt = $conn->prepare("SELECT * FROM tbl_student");
                            $stmt->execute();

                            $result = $stmt->fetchAll();
                            $counter = 1;

                            foreach ($result as $row) {
                                $studentID = $row["tbl_student_id"];
                                $studentName = $row["student_name"];
                                $studentAge = $row["student_age"];
                                $studentGender = $row["student_gender"];
                                $studentEmail = $row["student_email"];
                                $studentPhone = $row["student_phone"];
                                $studentCourse = $row["course_section"];
                                $studentPosition = $row["student_position"];
                                $qrCode = $row["generated_code"];
                            ?>

                                <tr>
                                    <td><?= $counter ?></td> <!-- Display the counter for row number -->
                                    <td id="studentName-<?= $studentID ?>"><?= $studentName ?></td>
                                    <td id="studentAge-<?= $studentID ?>"><?= $studentAge ?></td>
                                    <td id="studentGender-<?= $studentID ?>"><?= $studentGender ?></td>
                                    <td id="studentEmail-<?= $studentID ?>"><?= $studentEmail ?></td>
                                    <td id="studentPhone-<?= $studentID ?>"><?= $studentPhone ?></td>
                                    <td id="studentCourse-<?= $studentID ?>"><?= $studentCourse ?></td>
                                    <td id="studentPosition-<?= $studentID ?>"><?= $studentPosition ?></td>
                                    <td>
                                        <div class="action-button">
                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#qrCodeModal<?= $studentID ?>"><img
                                                    src="https://cdn-icons-png.flaticon.com/512/1341/1341632.png" alt=""
                                                    width="16"></button>

                                            <!-- QR Modal -->
                                            <div class="modal fade" id="qrCodeModal<?= $studentID ?>" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"><?= $studentName ?>'s QR Code</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $qrCode ?>"
                                                                alt="" width="300">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button class="btn btn-secondary btn-sm"
                                                onclick="updateStudent(<?= $studentID ?>)">&#128393;</button>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="deleteStudent(<?= $studentID ?>)">&#10006;</button>
                                        </div>
                                    </td>
                                </tr>

                            <?php
                                $counter++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    </div>
    <!-- Add Modal -->
    <div class="modal fade" id="addStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="addStudent" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudent">Add Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/add-student.php" method="POST">
                        <div class="form-group">
                            <label for="studentName">Full Name:</label>
                            <input type="text" class="form-control" id="studentName" name="student_name">
                        </div>
                        <div class="form-group">
                            <label for="studentAge">Age:</label>
                            <input type="number" class="form-control" id="studentAge" name="student_age">
                        </div>
                        <div class="form-group">
                            <label for="studentGender">Gender</label>
                            <input type="text" class="form-control" id="studentGender" name="student_gender">
                        </div>
                        <div class="form-group">
                            <label for="studentEmail">Email</label>
                            <input type="email" class="form-control" id="studentEmail" name="student_email">
                        </div>
                        <div class="form-group">
                            <label for="studentPhone">Phone</label>
                            <input type="text" class="form-control" id="studentPhone" name="student_phone">
                        </div>
                        <div class="form-group">
                            <label for="studentCourse">Station:</label>
                            <input type="text" class="form-control" id="studentCourse" name="course_section">
                        </div>
                        <div class="form-group">
                            <label for="studentCourse">Position:</label>
                            <input type="text" class="form-control" id="studentPosition" name="student_position">
                        </div>
                        <button type="button" class="btn btn-secondary form-control qr-generator"
                            onclick="generateQrCode()">Generate QR Code</button>

                        <div class="qr-con text-center" style="display: none;">
                            <input type="hidden" class="form-control" id="generatedCode" name="generated_code">
                            <p>Generated QR Code</p>
                            <img class="mb-4" src="" id="qrImg" alt="">
                        </div>
                        <div class="modal-footer modal-close" style="display: none;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Add List</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="updateStudent" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStudent">Update Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/update-student.php" method="POST">
                        <input type="hidden" class="form-control" id="updateStudentId" name="tbl_student_id">
                        <div class="form-group">
                            <label for="updateStudentName">Full Name:</label>
                            <input type="text" class="form-control" id="updateStudentName" name="student_name">
                        </div>
                        <div class="form-group">
                            <label for="studentAge">Age:</label>
                            <input type="number" class="form-control" id="updateStudentAge" name="student_age">
                        </div>
                        <div class="form-group">
                            <label for="studentGender">Gender:</label>
                            <input type="text" class="form-control" id="updateStudentGender" name="student_gender">
                        </div>
                        <div class="form-group">
                            <label for="studentEmail">Email:</label>
                            <input type="email" class="form-control" id="updateStudentEmail" name="student_email">
                        </div>
                        <div class="form-group">
                            <label for="studentPhone">Phone:</label>
                            <input type="text" class="form-control" id="updateStudentPhone" name="student_phone">
                        </div>
                        <div class="form-group">
                            <label for="updateStudentCourse">Station:</label>
                            <input type="text" class="form-control" id="updateStudentCourse" name="course_section">
                        </div>
                        <div class="form-group">
                            <label for="updateStudentPosition">Position:</label>
                            <input type="text" class="form-control" id="updateStudentPosition" name="student_position">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark">Update</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
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


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- Data Table -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <script>
        document.body.classList.add('blurred');

        function checkPassword() {
            const password = "istd_admin"; // Replace with your desired password
            const input = document.getElementById('passwordInput').value;

            if (input === password) {
                document.getElementById('loginOverlay').style.display = 'none';
                document.body.classList.remove('blurred');
            } else {
                alert("Incorrect password. Please try again.");
            }
        }
        $(document).ready(function() {
            $('#studentTable').DataTable();
        });

        function updateStudent(id) {
            $("#updateStudentModal").modal("show");

            let updateStudentId = id; // Adjusted to directly use the id passed to the function
            let updateStudentName = $("#studentName-" + id).text();
            let updateStudentAge = $("#studentAge-" + id).text();
            let updateStudentGender = $("#studentGender-" + id).text();
            let updateStudentEmail = $("#studentEmail-" + id).text();
            let updateStudentPhone = $("#studentPhone-" + id).text();
            let updateStudentCourse = $("#studentCourse-" + id).text();
            let updateStudentPosition = $("#studentPosition-" + id).text();

            $("#updateStudentId").val(updateStudentId);
            $("#updateStudentName").val(updateStudentName);
            $("#updateStudentAge").val(updateStudentAge);
            $("#updateStudentGender").val(updateStudentGender);
            $("#updateStudentEmail").val(updateStudentEmail);
            $("#updateStudentPhone").val(updateStudentPhone);
            $("#updateStudentCourse").val(updateStudentCourse);
            $("#updateStudentPosition").val(updateStudentPosition);
        }


        function deleteStudent(id) {
            if (confirm("Do you want to delete this member?")) {
                window.location = "./endpoint/delete-student.php?student=" + id;
            }
        }

        function generateRandomCode(length) {
            const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            let randomString = '';

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                randomString += characters.charAt(randomIndex);
            }

            return randomString;
        }

        function generateQrCode() {
            const qrImg = document.getElementById('qrImg');

            let text = generateRandomCode(10);
            $("#generatedCode").val(text);

            if (text === "") {
                alert("Please enter text to generate a QR code.");
                return;
            } else {
                const apiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(text)}`;

                qrImg.src = apiUrl;
                document.getElementById('studentName').style.pointerEvents = 'none';
                document.getElementById('studentCourse').style.pointerEvents = 'none';
                document.querySelector('.modal-close').style.display = '';
                document.querySelector('.qr-con').style.display = '';
                document.querySelector('.qr-generator').style.display = 'none';
            }
        }
    </script>

</body>

</html>