<?php

class Validation
{
  // Made By : Yusuf Limited

  protected $errors = [];
  protected $customMessages = [];

  public function setCustomMessages($messages)
  {
    $this->customMessages = $messages;
  }

  public function validate($postData, $fileData, $rules)
  {
    foreach ($rules as $field => $rule) {
      $rulesArray = explode('|', $rule);

      foreach ($rulesArray as $singleRule) {
        $ruleParts = explode(':', $singleRule);
        $ruleName = $ruleParts[0];

        $messageKey = $field . ':' . $ruleName;
        $customMessage = isset($this->customMessages[$messageKey]) ? $this->customMessages[$messageKey] : '';

        switch ($ruleName) {
          case 'required':
            if (!isset($postData[$field]) || empty($postData[$field])) {
              $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field is required.';
              $this->errors[$field][] = $errorMessage;
            }
            break;

          case 'email':
            if (isset($postData[$field]) && !filter_var($postData[$field], FILTER_VALIDATE_EMAIL)) {
              $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field must be a valid email address.';
              $this->errors[$field][] = $errorMessage;
            }
            break;

          case 'alpha':
            if (isset($postData[$field]) && !preg_match('/^[a-zA-Z\s]+$/', $postData[$field])) {
              $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field must only contain letters and spaces.';
              $this->errors[$field][] = $errorMessage;
            }
            break;

          case 'number':
            if (isset($postData[$field]) && !is_numeric($postData[$field])) {
              $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field must be a number.';
              $this->errors[$field][] = $errorMessage;
            }
            break;

          case 'min':
            if (isset($postData[$field]) && is_numeric($postData[$field]) && $postData[$field] < $ruleParts[1]) {
              $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field must be at least ' . $ruleParts[1] . '.';
              $this->errors[$field][] = $errorMessage;
            }
            break;

          case 'max':
            if (isset($postData[$field]) && is_numeric($postData[$field]) && $postData[$field] > $ruleParts[1]) {
              $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field must not exceed ' . $ruleParts[1] . '.';
              $this->errors[$field][] = $errorMessage;
            }
            break;

          case 'minlength':
            if (isset($postData[$field]) && strlen($postData[$field]) < $ruleParts[1]) {
              $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field must have a minimum length of ' . $ruleParts[1] . '.';
              $this->errors[$field][] = $errorMessage;
            }
            break;

          case 'maxlength':
            if (isset($postData[$field]) && strlen($postData[$field]) > $ruleParts[1]) {
              $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field must not exceed ' . $ruleParts[1] . ' characters.';
              $this->errors[$field][] = $errorMessage;
            }
            break;

          case 'nullable':
            // skip
            break;

            case 'required_file':
              if (!isset($fileData[$field]) || empty($fileData[$field]['tmp_name'])) {
                  $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field is required.';
                  $this->errors[$field][] = $errorMessage;
              }
              break;

          case 'image':
            if (isset($fileData[$field]) && !empty($fileData[$field]['tmp_name'])) {
              $allowedExtensions = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : ['jpg', 'jpeg', 'png', 'gif'];
              $fileExtension = pathinfo($fileData[$field]['name'], PATHINFO_EXTENSION);
              $maxFileSize = isset($ruleParts[2]) ? $ruleParts[2] : 0;

              if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field must be a valid image file (' . implode(', ', $allowedExtensions) . ').';
                $this->errors[$field][] = $errorMessage;
              }

              if ($maxFileSize > 0 && $fileData[$field]['size'] > $maxFileSize) {
                $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field must not exceed ' . ($maxFileSize / 1024 / 1024) . 'MB.';
                $this->errors[$field][] = $errorMessage;
              }
            }
            break;

          case 'file':
            if (isset($fileData[$field]) && !empty($fileData[$field]['tmp_name'])) {
              $allowedExtensions = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];
              $fileExtension = pathinfo($fileData[$field]['name'], PATHINFO_EXTENSION);
              $maxFileSize = isset($ruleParts[2]) ? $ruleParts[2] : 0;

              if (!empty($allowedExtensions) && !in_array(strtolower($fileExtension), $allowedExtensions)) {
                $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field must be a valid file (' . implode(', ', $allowedExtensions) . ').';
                $this->errors[$field][] = $errorMessage;
              }

              if ($maxFileSize > 0 && $fileData[$field]['size'] > $maxFileSize) {
                $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field must not exceed ' . ($maxFileSize / 1024 / 1024) . 'MB.';
                $this->errors[$field][] = $errorMessage;
              }
            }
            break;

          case 'array':
            if (isset($postData[$field]) && !is_array($postData[$field])) {
              $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field must be an array.';
              $this->errors[$field][] = $errorMessage;
            }
            break;

          case 'captcha_match':
            $generatedCaptcha = isset($postData['captcha_generate']) ? strtoupper(str_replace(' ', '', $postData['captcha_generate'])) : '';
            $userCaptcha = isset($postData[$field]) ? strtoupper(str_replace(' ', '', $postData[$field])) : '';

            if (empty($generatedCaptcha) || empty($userCaptcha) || $userCaptcha !== $generatedCaptcha) {
              $errorMessage = !empty($customMessage) ? $customMessage : 'The ' . $field . ' field does not match the generated captcha.';
              $this->errors[$field][] = $errorMessage;
            }
            break;
        }
      }
    }
  }

  public function getErrors()
  {
    return $this->errors;
  }

  public function hasErrors()
  {
    return !empty($this->errors);
  }
}