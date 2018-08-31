<?php

// Se alla fel under development.
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//sätter standard tidszonen till Europa/Stockholm
date_default_timezone_set("Europe/Stockholm");

//gör csv filen till en array och loopar igenom den
function getCountryCodePrice(){
    $csv = array_map('str_getcsv', file('lista.csv'));
    $countryCode = func_get_args();
    
    //loopar igenom alla landskoder som man skriver in som argument till funktionen
    foreach ($countryCode as $id => $letters) {
        $findCountryCode = false;
        $totalPrice = [];
        $WrongID = [];
        $successOrNot;
        //lopar igenom alla rader som finns i .csv filen
        foreach ($csv as $key => $arrayItem) {
            //loopar igenom varje rad i .csv filen som finns
            foreach ($arrayItem as $key2 => $value) {
                //kollar om landskoden finns med i någon sträng
                if($arrayItem[0] && strpos($value, $letters)){
                    //kollar om alla ID fölljer samma struktur och räknar ut total värdet av dem
                    if(preg_match('/^#[A-Z]{2}[0-9]{6}$/', $value)){
                        //sätter $findCountryCode till true om landskoden finns och följer standarden på ID
                    $findCountryCode = true;
                        //räknar ihop det totala priset på varje ID och skickar in varje total pris in i en array för förvaring
                        $totItemPrice = $arrayItem[1] * $arrayItem[2];
                        array_push($totalPrice, $totItemPrice);
                    }else{
                        //pushar in varge felaktigt id in en array
                        array_push($WrongID, $value);
                    }
                }
            }
        }
        //retunerar Success eller Failure och felmedelande om landskoden inte finns
        if($findCountryCode){
            $successOrNot = "Success";
        }else{
            $successOrNot = "Failure!" . " " . $letters . " " . "hittades inte";
        }

        //Gör en fil och skriver in status, landskod och total pris i filen
        $string = $successOrNot . ", " . $letters . ", " . array_sum($totalPrice);
        $fileName = $letters . "-" . date("Ymd") . "-" . date("His") . ".csv";
        $fileHandle = fopen($fileName, "w+");
        
        fwrite($fileHandle, $string);
        fclose($fileHandle);

        //skriver ut landskod, vilka ID som är felaktiga, total priset och status
        echo "Landskod: " . $letters . "<br/>";
        foreach ($WrongID as $a => $b) {
            echo $b . " är felaktigt <br/>";
        }
        echo "Total Price: " . array_sum($totalPrice) . "<br/>" . "Status: " . $successOrNot . "<br/><br/>";
    }
}
echo getCountryCodePrice("SE", "US", "RU", "EU");