<?php
    class ContactSanitizer
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

        public static function sanitizePhoneNumber($phoneNumber) {
            return preg_replace('/\D/', '', trim($phoneNumber));
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
            $arraySanitized["phoneNumber"] = self::sanitizePhoneNumber($arrayData['phoneNumber']);

            return $arraySanitized;
        }
    }
