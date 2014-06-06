<?php
/**
 * @author	Andre Sieverding https://github.com/Teddy95
 * @license	MIT http://opensource.org/licenses/MIT
 * 
 * The MIT License (MIT)
 * 
 * Copyright (c) 2014 Andre Sieverding
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Teddy95\Modeler;

/**
 * Template class
 */
class template
{

	/**
	 * Folder which contains the templates.
	 */
	private static $templateDirectory = false;

	/**
	 * Folder which contains the language files.
	 */
	private static $languageDirectory = false;

	/**
	 * Standard delimiters.
	 */
	private static $leftDelimiter = '{{ ';
	private static $rightDelimiter = ' }}';

	/**
	 * Delimiters for functions.
	 */
	private static $leftFunctionDelimiter = '\{\[\ ';
	private static $rightFunctionDelimiter = '\ \]\}';

	/**
	 * Delimiters for comments.
	 */
	private static $leftCommentDelimiter = '\{\*\ ';
	private static $rightCommentDelimiter = '\ \*\}';

	/**
	 * Delimiters for language variables.
	 */
	private static $leftLangvarDelimiter = '\{\(\ ';
	private static $rightLangvarDelimiter = '\ \)\}';

	/**
	 * Delimiters for reserved variables.
	 */
	private static $leftResvarDelimiter = '\{\|\ ';
	private static $rightResvarDelimiter = '\ \|\}';

	/**
	 * File name and content of the template.
	 */
	private static $templateFile = false;
	private static $templateName = false;
	private static $templateContent = false;

	/**
	 * @param string	$templateDirectory
	 * @param string	$languageDirectory
	 */
	public function __construct ($templateDirectory = false, $languageDirectory = false) {

		if (substr($templateDirectory, -1, 1) != '/') {
			$templateDirectory .= '/';
		}

		if (substr($languageDirectory, -1, 1) != '/') {
			$languageDirectory .= '/';
		}

		self::$templateDirectory = $templateDirectory;
		self::$languageDirectory = $languageDirectory;

		return;

	}

	/**
	 * @param string	$templateDirectory
	 */
	public static function set_template_directory ($templateDirectory) {

		if (substr($templateDirectory, -1, 1) != '/') {
			$templateDirectory .= '/';
		}

		self::$templateDirectory = $templateDirectory;

		return;

	}

	/**
	 * @param string	$languageDirectory
	 */
	public static function set_language_directory ($languageDirectory) {

		if (substr($languageDirectory, -1, 1) != '/') {
			$languageDirectory .= '/';
		}

		self::$languageDirectory = $languageDirectory;

		return;

	}

	/**
	 * @param string	$templateFile
	 *
	 * @return bool		Returns FALSE on failure
	 */
	public static function load_template ($templateFile) {

		self::$templateFile = $templateFile;
		self::$templateName = basename($templateFile);

		if (isset(self::$templateFile) === true && empty(self::$templateFile) === false) {
			if (file_exists(self::$templateFile)) {
				self::$templateContent = file_get_contents(self::$templateFile);
			} else {
				return false;
			}
		} else {
			return false;
		}

		self::parse_functions();

		return;

	}

	/**
	 * @param string	$name
	 * @param string	$replacement
	 */
	public static function assign ($name, $replacement) {

		self::$templateContent = str_replace(self::$leftDelimiter . $name . self::$rightDelimiter, $replacement, self::$templateContent);

		return;

	}

	/**
	 * Replace language variables.
	 */
	private static function replace_language_variables () {

		$languageDirectory = self::$languageDirectory;

		while (preg_match("/" . self::$leftLangvarDelimiter . "(.*)\.(.*)" . self::$rightLangvarDelimiter . "/isUe", self::$templateContent, $matches)) {
			$language = json_decode(file_get_contents($languageDirectory . $matches[1] . '.json'), true);
			$langPieces = explode('.', $matches[2]);
			$langvar = "\$language";
			
			for ($i = 0; $i < count($langPieces); $i++) {
				$langvar .= "[" . $langPieces[$i] . "]";
			}

			self::$templateContent = preg_replace("/" . self::$leftLangvarDelimiter . "(.*)\.(.*)" . self::$rightLangvarDelimiter . "/isUe", "\\" . $langvar, self::$templateContent);
		}

		return;

	}

	/**
	 * Replace reserved variables.
	 */
	private static function replace_reserved_variables () {

		while (preg_match("/" . self::$leftResvarDelimiter . "REQUEST\-\>(.*)" . self::$rightResvarDelimiter . "/isUe", self::$templateContent)) {
			self::$templateContent = preg_replace("/" . self::$leftResvarDelimiter . "REQUEST\-\>(.*)" . self::$rightResvarDelimiter . "/isUe", "\$_REQUEST['\\1']", self::$templateContent);
		}

		while (preg_match("/" . self::$leftResvarDelimiter . "GET\-\>(.*)" . self::$rightResvarDelimiter . "/isUe", self::$templateContent)) {
			self::$templateContent = preg_replace("/" . self::$leftResvarDelimiter . "GET\-\>(.*)" . self::$rightResvarDelimiter . "/isUe", "\$_GET['\\1']", self::$templateContent);
		}

		while (preg_match("/" . self::$leftResvarDelimiter . "POST\-\>(.*)" . self::$rightResvarDelimiter . "/isUe", self::$templateContent)) {
			self::$templateContent = preg_replace("/" . self::$leftResvarDelimiter . "POST\-\>(.*)" . self::$rightResvarDelimiter . "/isUe", "\$_POST['\\1']", self::$templateContent);
		}

		while (preg_match("/" . self::$leftResvarDelimiter . "SERVER\-\>(.*)" . self::$rightResvarDelimiter . "/isUe", self::$templateContent)) {
			self::$templateContent = preg_replace("/" . self::$leftResvarDelimiter . "SERVER\-\>(.*)" . self::$rightResvarDelimiter . "/isUe", "\$_SERVER['\\1']", self::$templateContent);
		}

		while (preg_match("/" . self::$leftResvarDelimiter . "SESSION\-\>(.*)" . self::$rightResvarDelimiter . "/isUe", self::$templateContent)) {
			self::$templateContent = preg_replace("/" . self::$leftResvarDelimiter . "SESSION\-\>(.*)" . self::$rightResvarDelimiter . "/isUe", "\$_SESSION['\\1']", self::$templateContent);
		}

		while (preg_match("/" . self::$leftResvarDelimiter . "COOKIE\-\>(.*)" . self::$rightResvarDelimiter . "/isUe", self::$templateContent)) {
			self::$templateContent = preg_replace("/" . self::$leftResvarDelimiter . "COOKIE\-\>(.*)" . self::$rightResvarDelimiter . "/isUe", "\$_COOKIE['\\1']", self::$templateContent);
		}

		return;

	}

	/**
	 * Parse functions, delete comments and replace language variables.
	 */
	private static function parse_functions () {

		/**
		 * Parse functions.
		 */
		$templateDirectory = self::$templateDirectory;
		
		// Includes
		while (preg_match("/" . self::$leftFunctionDelimiter . "include file=\"(.*)\.(.*)\"" . self::$rightFunctionDelimiter . "/isUe", self::$templateContent)) {
			self::$templateContent = preg_replace("/" . self::$leftFunctionDelimiter . "include file=\"(.*)\.(.*)\"" . self::$rightFunctionDelimiter . "/isUe", "file_get_contents(\$templateDirectory . '\\1' . '.' . '\\2')", self::$templateContent);
		}

		/**
		 * Delete comments from template.
		 */
		self::$templateContent = preg_replace("/" . self::$leftCommentDelimiter . "(.*)" . self::$rightCommentDelimiter . "/isUe", '', self::$templateContent);

		/**
		 * Replace language variables.
		 */
		self::replace_language_variables();

		/**
		 * Replace REQUEST, GET, POST, SERVER, SESSION and COOKIE variables.
		 */
		self::replace_reserved_variables();

		return;

	}

	/**
	 * @param string	$eval
	 *
	 * Output template.
	 */
	public static function display ($eval = false) {

		if ($eval === true) {
			eval(' ?>' . self::$templateContent . '<?php ');
		} else {
			echo self::$templateContent;
		}

		return;

	}
	
}
?>
