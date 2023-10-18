<?php
    class ContactSanitizerValidator
    {
        public static function sanitizeName($name) {
            return htmlspecialchars(trim($name));
        }
    
        public static function sanitizeEmail($email) {
            return trim($email);
        }
    
        public static function sanitizeSubject($subject) {
            return htmlspecialchars(trim($subject));
        }
    
        public static function sanitizeMessage($message) {
            return htmlspecialchars(trim($message));
        }

        public static function sanitize($arrayData)
        {
            $arraySanitized = [];

            $arraySanitized["name"] = self::sanitizeName($arrayData['name']);
            $arraySanitized["email"] = self::sanitizeEmail($arrayData['email']);
            $arraySanitized["subject"] = self::sanitizeSubject($arrayData['subject']);
            $arraySanitized["message"] = self::sanitizeMessage($arrayData['message']);
    
            return $arraySanitized;
        }

        public static function validateName($name) {
            return strlen($name) > 1 ? 0 : 1;
        }
    
        public static function validateEmail($email) {
            if (strlen($email) > 6 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return 0;
            } else {
                return 1;
            }
        }
    
        public static function validateSubject($subject) {
            return empty($subject) ? 1 : 0;
        }
    
        public static function validateMessage($message) {
            return strlen($message) > 3 ? 0 : 1;
        }
    
        public static function validate($arrayData) {
            $errors = [];
    
            $errors['name'] = self::validateName($arrayData['name']);
            $errors['email'] = self::validateEmail($arrayData['email']);
            $errors['subject'] = self::validateSubject($arrayData['subject']);
            $errors['message'] = self::validateMessage($arrayData['message']);
    
            return $errors;
        }
    }
