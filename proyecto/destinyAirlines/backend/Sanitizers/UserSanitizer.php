<?php
    class UserSanitizer
    {
/*
        //SANEAR TODOS UNO POR UNO QUIZÁS SEA LO MEJOR
            'title'                 
            'firstName'             
            'lastName'              
            'tonCity'               
            'streetAddress'         
            'zipCode'               
            'country'               
            'emailAddress'          
            'password'              
            'phoneNumber1'          
            'phoneNumber2'          
            'phoneNumber3'          
            'companyName'           
            'companyTaxNumber'      
            'companyPhoneNumber'    
*/
        public static function sanitizeTitle($title) {
            return ;
        }

        public static function sanitize($arrayData)
        {
            $arraySanitized = [];

            $arraySanitized["title"] = self::sanitizeTitle($arrayData['title']);

            return $arraySanitized;
        }
    }
