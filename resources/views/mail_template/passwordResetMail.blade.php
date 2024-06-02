<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset Mail</title>
</head>
<style>
    label{
        display: block;
        font-size: 13px;
    }
</style>
<body>

    <h5>Hey {{$name}} !</h5>
   <p class="text-justify">

    Your OTP is {{$otp}}
    We’re sending this email to reset your account password at www.shyamoni.com.
   </p>


   <label for="">Cheers</label><br>
   <label for="">Shyamoni’s Customer Service Team</label><br>
   <label for="">Email: careshyamoni@gmail.com</label><br>
   <label for="">Mobile: +919387687985</label><br>
   <label for="">WhatsApp: +919387687985</label><br>


</body>
</html>
