<?php
/**
 * JSON Schema validator
 *
 * @author  g105b <g105b@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    https://github.com/g105b/Json-Schema-Validator
 */
class Json_Schema_Validator {
	const TYPE_ANYOF = "anyOf";
	const TYPE_ALLOF = "allOf";
	const TYPE_ONEOF = "oneOf";

	public $check_mode = false;
	protected $errors = [];

	public function validate($data, $schema) {
		$this->errors = [];

		if(!is_object($data)){
			$data = json_decode($data);
			if($data === null){
				$this->addError(json_last_error_msg());
				return $this->getErrors();
			}
		}

		$this->validateSchema($data, $schema);
		return $this->getErrors();
	}

	protected function validateSchema($data, $schema) {
		if(!is_object($schema)){
			$schema = json_decode($schema);
		}

		if(isset($schema->type)) {
			$this->validateType($data, $schema->type);
		}

		foreach($schema as $key => $value) {
			if(method_exists($this, "validate_{$key}")) {
				$this->{"validate_{$key}"}($data, $value);
			}
		}

		if($this->check_mode) {
			if(count($this->errors) > 0) {
				return false;
			}
		}
		return true;
	}

	protected function validate_properties($data, $schema) {
		foreach($schema as $key => $value) {
			if(isset($data->$key)) {
				$this->validateSchema($data->$key, $value);
			}
		}
	}

	protected function validate_required($data, $schema) {
		foreach($schema as $key) {
			if(!isset($data->$key)) {
				$this->addError("Required property `{$key}` is not set");
			}
		}
	}

	protected function validate_minimum($data, $schema) {
		if($data < $schema) {
			$this->addError("Value `{$data}` is less than minimum `{$schema}`");
		}
	}

	protected function validate_maximum($data, $schema) {
		if($data > $schema) {
			$this->addError("Value `{$data}` is greater than maximum `{$schema}`");
		}
	}

	protected function validate_minItems($data, $schema) {
		if(count($data) < $schema) {
			$this->addError("Array count `{$data}` is less than minItems `{$schema}`");
		}
	}

	protected function validate_maxItems($data, $schema) {
		if(count($data) > $schema) {
			$this->addError("Array count `{$data}` is greater than maxItems `{$schema}`");
		}
	}

	protected function validate_uniqueItems($data, $schema) {
		if($schema) {
			if(count($data) !== count(array_unique($data))) {
				$this->addError("Array values are not unique");
			}
		}
	}

	protected function validate_pattern($data, $schema) {
		if(!preg_match("/{$schema}/", $data)) {
			$this->addError("Value `{$data}` does not match pattern `{$schema}`");
		}
	}

	protected function validate_minLength($data, $schema) {
		if(strlen($data) < $schema) {
			$this->addError("String length of `{$data}` is less than minLength `{$schema}`");
		}
	}

	protected function validate_maxLength($data, $schema) {
		if(strlen($data) > $schema) {
			$this->addError("String length of `{$data}` is greater than maxLength `{$schema}`");
		}
	}

	protected function validate_enum($data, $schema) {
		if(!in_array($data, $schema)) {
			$this->addError("Value `{$data}` is not in enum list");
		}
	}

	protected function validate_items($data, $schema) {
		if(is_array($data)) {
			foreach($data as $value) {
				$this->validateSchema($value, $schema);
			}
		}
	}

	protected function validate_anyOf($data, $schema) {
		$this->validateOf($data, $schema, self::TYPE_ANYOF);
	}

	protected function validate_allOf($data, $schema) {
		$this->validateOf($data, $schema, self::TYPE_ALLOF);
	}

	protected function validate_oneOf($data, $schema) {
		$this->validateOf($data, $schema, self::TYPE_ONEOF);
	}

	protected function validateOf($data, $schema, $type) {
		$errorsBefore = $this->errors;
		$matched = 0;

		foreach($schema as $subSchema) {
			$this->errors = [];
			$this->validateSchema($data, $subSchema);
			if(empty($this->errors)) {
				$matched++;
			}
		}

		$this->errors = $errorsBefore;

		switch($type) {
		case self::TYPE_ANYOF:
			if($matched < 1) {
				$this->addError("Failed `anyOf` schema validation");
			}
			break;
		case self::TYPE_ALLOF:
			if($matched < count($schema)) {
				$this->addError("Failed `allOf` schema validation");
			}
			break;
		case self::TYPE_ONEOF:
			if($matched !== 1) {
				$this->addError("Failed `oneOf` schema validation");
			}
			break;
		}
	}

	protected function validateType($data, $type) {
		switch($type) {
		case "object":
			if(!is_object($data)) {
				$this->addError("Not an object");
			}
			break;
		case "array":
			if(!is_array($data)) {
				$this->addError("Not an array");
			}
			break;
		case "string":
			if(!is_string($data)) {
				$this->addError("Not a string");
			}
			break;
		case "number":
			if(!is_numeric($data)) {
				$this->addError("Not a number");
			}
			break;
		case "integer":
			if(!is_int($data)) {
				$this->addError("Not an integer");
			}
			break;
		case "boolean":
			if(!is_bool($data)) {
				$this->addError("Not a boolean");
			}
			break;
		case "null":
			if(!is_null($data)) {
				$this->addError("Not null");
			}
			break;
		}
	}

	public function addError($message) {
		$this->errors[] = $message;
	}

	public function getErrors() {
		return $this->errors;
	}
}