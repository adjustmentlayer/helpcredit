<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])){

    $token = "";
    $chat_id = "";

    //the message to be sent to the Telegram chat
    $message = "";

    // error array to keep track of errors
    $errors = [];

    // error messages
    $messages = [
        "name_required" => "ПІБ є обов`язковим для заповнення",
        "phone_required" =>"Телефон є обов`язковим для заповнення",
        "phone_match" =>"Введіть телефон у форматі 380xxx-xxxx"
    ];
    
    // populate form data 
    $arr_form_data = array(
        "name" => array(
            "value" => $_POST['name'] ?? "",
            "description" => "ПІБ",
            "emoji"=>"\xE2\x9C\x8F"
        ),
        "phone" => array(
            "value" => $_POST['phone'] ?? "",
            "description" => "Телефон",
            "emoji"=>"\xF0\x9F\x93\x9E"
        ),
        "address" => array(
            "value" => $_POST['address'] ?? "",
            "description" => "Місце проживання",
            "emoji"=>"\xF0\x9F\x8F\xA0"
        ),
        "messanger" => array(
            "value" => $_POST['messanger'] ?? "",
            "description" => "Бажаный месенджер",
            "emoji"=>"\xE2\xAD\x90"
        ),
        "message" => array(
            "value" => $_POST['message'] ?? "",
            "description" => "Повідомлення",
            "emoji"=>"\xF0\x9F\x93\xA8"
        )
    );

    // validate form data
    if($arr_form_data['name']['value'] == ""){
        $errors["name_required"] ="";
    }
    if($arr_form_data['phone']['value'] == ""){
        $errors["phone_required"] = "";
    }
    if(!preg_match('/^\+?3?8?\(?0\d{2}\)?\-?\d{3}\-?\d{2}\d{2}$/',$arr_form_data['phone']['value'])){
        $errors["phone_match"] = "";
    }
        
    // execute if user typed valid data
    if(empty($errors)){

        // form the content of the message
        $message="\xF0\x9F\x93\x83 <b>Нова заявка</b> %0A %0A";
        foreach($arr_form_data as $key => $value){
            $message .= $value['emoji']." <b>".$value['description']."</b>: ".$value['value'].".%0A";
        }

        // send the message
        $sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}
        &parse_mode=html&text={$message}","r");

        //check if the message has been sent
        if($sendToTelegram){
            $response['success'] = "Дякуємо! Ваша заявка надіслана!";
        }else{
            $response['error'] = "Вибачте! Сталася помилка при відправці повідомлення";
        }
        
    }
 
}

// helper functions
function show_error($name){
    global $errors;
    global $messages;

    if(isset($errors)){
        if(array_key_exists($name, $errors)){
            echo "<div class='invalid-feedback'>" . $messages[$name] . "</div>";
        }
    }
}

function indicate_error($name){
    global $errors;
    if(isset($errors)){
        if(isset($errors[$name])){
            echo "is-invalid ";
        } 
    }
}
    

?>