<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <title>MED-CONNECT - user</title>
</head>
<body>
    <div class="page">
        <div class="main-content">
            
            <div class="main-box">
                <div class="box-field" style="height:100%; width:100%">
                        <div class="box" style="width: 500px; height:500px">
                            <div class="box-title" style="margin-bottom: 24px;">Mani ieraksti</div>
                            <!--php function that takes all of the data user has sent in the database (using user id) from the previous form and displays it-->
                            
                            <div class="info-box">
                                <div class="info-box-title">
                                    <!-- get patient name that made this record in the db and place it here-->
                                    user
                                </div>
                                <!-- get the text that is in the record and place it here -->
                                    ttt

                                <div class="info-box-date">
                                    <!--display date for this record-->
                                    15.08
                                </div>                                    
                                 <div class="info-box-response" id="record_response">
                                      <div class="info-box-title" id="doctor_id">
                                      Response from 
                                      <!-- insert doctor name here from the database doctor_responses(	id	record_id	patient_id	doctor_id	record_response	)-->
                                      </div>
                                      <!-- display the record response here-->
                                      ---
                                  </div>
                            </div>
                        </div>
                                

                        <div class="box" style="width: 350px; height:500px">
                            <div class="box-title">Veikt jaunu ierakstu</div>
                            <form class="form" action="/" method="post">
                                <div class="form-fieldset">
                                    <!-- get user id and name from database using php function-->
                                    <div>
                                        <p>Ziņojums no</p>
                                        <p>name<!-- display user name here that was taken from user id in the database--></p>
                                    </div>
                                    <!--php function that takes doctor id and name from the database-->
                                    <div class="form-group">
                                        <select class="form-select">
                                            <option>Izvēlies ārstu</option>
                                            <option><!--add a doctor name from database--></option>
                                            <option><!--add a doctor name from database--></option>
                                            <!--php function that will make a option for every doctor in the database-->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-textarea" placeholder="Informācija par veselības stāvokli"></textarea>
                                    </div>
    
    
                                    <!--insert php function that will add the data in the database for this user id-->
                                    <button class="main-button" type="submit">Nosūtīt ziņojumu</button>
    
                                </div>
                            </form>
                        </div>

                        <div class="box" style="width: 300px; height:500px">
                            <div class="box-title">Visi ārsti</div>
                            <!--php function that takes all of the doctors that is in the database (using doctor id) from the database and displays it-->


                            <!-- get all of the information about the doctors in the database-->
                            <div class="info-box" style="flex-direction: column;">
                                <div class="info-box-title">
                                    <!-- get doctor name from the database and place it here-->
                                    mane
                                </div>
                                <!-- get the doctor contact info from database and place it here-->
                                    ttt
                            </div>

                        </div>
                    
                </div>

            </div> <!-- /* main box -->

            <footer class="center-horizontal" style="display: flex; flex-direction: row; justify-content: end;">
                <button class="red-button" onclick="document.location.href='log-out.php?id<?php echo $_GET['id']?>';">Iziet</button>
            </footer>

        </div> <!-- /* main content -->
    </div> <!-- /* page -->

</body>
</html>   