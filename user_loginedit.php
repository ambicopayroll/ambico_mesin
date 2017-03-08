<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "user_logininfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$user_login_edit = NULL; // Initialize page object first

class cuser_login_edit extends cuser_login {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'user_login';

	// Page object name
	var $PageObjName = 'user_login_edit';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (user_login)
		if (!isset($GLOBALS["user_login"]) || get_class($GLOBALS["user_login"]) == "cuser_login") {
			$GLOBALS["user_login"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["user_login"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'user_login', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_user();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("user_loginlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->login_id->SetVisibility();
		$this->login_pwd->SetVisibility();
		$this->grp_user_id->SetVisibility();
		$this->lastupdate_date->SetVisibility();
		$this->lastupdate_user->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $user_login;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($user_login);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load key from QueryString
		if (@$_GET["login_id"] <> "") {
			$this->login_id->setQueryStringValue($_GET["login_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->login_id->CurrentValue == "") {
			$this->Page_Terminate("user_loginlist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("user_loginlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "user_loginlist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->login_id->FldIsDetailKey) {
			$this->login_id->setFormValue($objForm->GetValue("x_login_id"));
		}
		if (!$this->login_pwd->FldIsDetailKey) {
			$this->login_pwd->setFormValue($objForm->GetValue("x_login_pwd"));
		}
		if (!$this->grp_user_id->FldIsDetailKey) {
			$this->grp_user_id->setFormValue($objForm->GetValue("x_grp_user_id"));
		}
		if (!$this->lastupdate_date->FldIsDetailKey) {
			$this->lastupdate_date->setFormValue($objForm->GetValue("x_lastupdate_date"));
			$this->lastupdate_date->CurrentValue = ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0);
		}
		if (!$this->lastupdate_user->FldIsDetailKey) {
			$this->lastupdate_user->setFormValue($objForm->GetValue("x_lastupdate_user"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->login_id->CurrentValue = $this->login_id->FormValue;
		$this->login_pwd->CurrentValue = $this->login_pwd->FormValue;
		$this->grp_user_id->CurrentValue = $this->grp_user_id->FormValue;
		$this->lastupdate_date->CurrentValue = $this->lastupdate_date->FormValue;
		$this->lastupdate_date->CurrentValue = ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0);
		$this->lastupdate_user->CurrentValue = $this->lastupdate_user->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->login_id->setDbValue($rs->fields('login_id'));
		$this->login_pwd->setDbValue($rs->fields('login_pwd'));
		$this->grp_user_id->setDbValue($rs->fields('grp_user_id'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->login_id->DbValue = $row['login_id'];
		$this->login_pwd->DbValue = $row['login_pwd'];
		$this->grp_user_id->DbValue = $row['grp_user_id'];
		$this->lastupdate_date->DbValue = $row['lastupdate_date'];
		$this->lastupdate_user->DbValue = $row['lastupdate_user'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// login_id
		// login_pwd
		// grp_user_id
		// lastupdate_date
		// lastupdate_user

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// login_id
		$this->login_id->ViewValue = $this->login_id->CurrentValue;
		$this->login_id->ViewCustomAttributes = "";

		// login_pwd
		$this->login_pwd->ViewValue = $this->login_pwd->CurrentValue;
		$this->login_pwd->ViewCustomAttributes = "";

		// grp_user_id
		$this->grp_user_id->ViewValue = $this->grp_user_id->CurrentValue;
		$this->grp_user_id->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

			// login_id
			$this->login_id->LinkCustomAttributes = "";
			$this->login_id->HrefValue = "";
			$this->login_id->TooltipValue = "";

			// login_pwd
			$this->login_pwd->LinkCustomAttributes = "";
			$this->login_pwd->HrefValue = "";
			$this->login_pwd->TooltipValue = "";

			// grp_user_id
			$this->grp_user_id->LinkCustomAttributes = "";
			$this->grp_user_id->HrefValue = "";
			$this->grp_user_id->TooltipValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";
			$this->lastupdate_date->TooltipValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
			$this->lastupdate_user->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// login_id
			$this->login_id->EditAttrs["class"] = "form-control";
			$this->login_id->EditCustomAttributes = "";
			$this->login_id->EditValue = $this->login_id->CurrentValue;
			$this->login_id->ViewCustomAttributes = "";

			// login_pwd
			$this->login_pwd->EditAttrs["class"] = "form-control";
			$this->login_pwd->EditCustomAttributes = "";
			$this->login_pwd->EditValue = ew_HtmlEncode($this->login_pwd->CurrentValue);
			$this->login_pwd->PlaceHolder = ew_RemoveHtml($this->login_pwd->FldCaption());

			// grp_user_id
			$this->grp_user_id->EditAttrs["class"] = "form-control";
			$this->grp_user_id->EditCustomAttributes = "";
			$this->grp_user_id->EditValue = ew_HtmlEncode($this->grp_user_id->CurrentValue);
			$this->grp_user_id->PlaceHolder = ew_RemoveHtml($this->grp_user_id->FldCaption());

			// lastupdate_date
			$this->lastupdate_date->EditAttrs["class"] = "form-control";
			$this->lastupdate_date->EditCustomAttributes = "";
			$this->lastupdate_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->lastupdate_date->CurrentValue, 8));
			$this->lastupdate_date->PlaceHolder = ew_RemoveHtml($this->lastupdate_date->FldCaption());

			// lastupdate_user
			$this->lastupdate_user->EditAttrs["class"] = "form-control";
			$this->lastupdate_user->EditCustomAttributes = "";
			$this->lastupdate_user->EditValue = ew_HtmlEncode($this->lastupdate_user->CurrentValue);
			$this->lastupdate_user->PlaceHolder = ew_RemoveHtml($this->lastupdate_user->FldCaption());

			// Edit refer script
			// login_id

			$this->login_id->LinkCustomAttributes = "";
			$this->login_id->HrefValue = "";

			// login_pwd
			$this->login_pwd->LinkCustomAttributes = "";
			$this->login_pwd->HrefValue = "";

			// grp_user_id
			$this->grp_user_id->LinkCustomAttributes = "";
			$this->grp_user_id->HrefValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->login_id->FldIsDetailKey && !is_null($this->login_id->FormValue) && $this->login_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->login_id->FldCaption(), $this->login_id->ReqErrMsg));
		}
		if (!$this->login_pwd->FldIsDetailKey && !is_null($this->login_pwd->FormValue) && $this->login_pwd->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->login_pwd->FldCaption(), $this->login_pwd->ReqErrMsg));
		}
		if (!$this->grp_user_id->FldIsDetailKey && !is_null($this->grp_user_id->FormValue) && $this->grp_user_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->grp_user_id->FldCaption(), $this->grp_user_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->grp_user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->grp_user_id->FldErrMsg());
		}
		if (!$this->lastupdate_date->FldIsDetailKey && !is_null($this->lastupdate_date->FormValue) && $this->lastupdate_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastupdate_date->FldCaption(), $this->lastupdate_date->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->lastupdate_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->lastupdate_date->FldErrMsg());
		}
		if (!$this->lastupdate_user->FldIsDetailKey && !is_null($this->lastupdate_user->FormValue) && $this->lastupdate_user->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastupdate_user->FldCaption(), $this->lastupdate_user->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// login_id
			// login_pwd

			$this->login_pwd->SetDbValueDef($rsnew, $this->login_pwd->CurrentValue, "", $this->login_pwd->ReadOnly);

			// grp_user_id
			$this->grp_user_id->SetDbValueDef($rsnew, $this->grp_user_id->CurrentValue, 0, $this->grp_user_id->ReadOnly);

			// lastupdate_date
			$this->lastupdate_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0), ew_CurrentDate(), $this->lastupdate_date->ReadOnly);

			// lastupdate_user
			$this->lastupdate_user->SetDbValueDef($rsnew, $this->lastupdate_user->CurrentValue, "", $this->lastupdate_user->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("user_loginlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($user_login_edit)) $user_login_edit = new cuser_login_edit();

// Page init
$user_login_edit->Page_Init();

// Page main
$user_login_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$user_login_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fuser_loginedit = new ew_Form("fuser_loginedit", "edit");

// Validate form
fuser_loginedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_login_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user_login->login_id->FldCaption(), $user_login->login_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_login_pwd");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user_login->login_pwd->FldCaption(), $user_login->login_pwd->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_grp_user_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user_login->grp_user_id->FldCaption(), $user_login->grp_user_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_grp_user_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($user_login->grp_user_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user_login->lastupdate_date->FldCaption(), $user_login->lastupdate_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($user_login->lastupdate_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user_login->lastupdate_user->FldCaption(), $user_login->lastupdate_user->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fuser_loginedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuser_loginedit.ValidateRequired = true;
<?php } else { ?>
fuser_loginedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$user_login_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $user_login_edit->ShowPageHeader(); ?>
<?php
$user_login_edit->ShowMessage();
?>
<form name="fuser_loginedit" id="fuser_loginedit" class="<?php echo $user_login_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($user_login_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $user_login_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="user_login">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($user_login_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($user_login->login_id->Visible) { // login_id ?>
	<div id="r_login_id" class="form-group">
		<label id="elh_user_login_login_id" for="x_login_id" class="col-sm-2 control-label ewLabel"><?php echo $user_login->login_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user_login->login_id->CellAttributes() ?>>
<span id="el_user_login_login_id">
<span<?php echo $user_login->login_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_login->login_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="user_login" data-field="x_login_id" name="x_login_id" id="x_login_id" value="<?php echo ew_HtmlEncode($user_login->login_id->CurrentValue) ?>">
<?php echo $user_login->login_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user_login->login_pwd->Visible) { // login_pwd ?>
	<div id="r_login_pwd" class="form-group">
		<label id="elh_user_login_login_pwd" for="x_login_pwd" class="col-sm-2 control-label ewLabel"><?php echo $user_login->login_pwd->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user_login->login_pwd->CellAttributes() ?>>
<span id="el_user_login_login_pwd">
<input type="text" data-table="user_login" data-field="x_login_pwd" name="x_login_pwd" id="x_login_pwd" size="30" maxlength="32" placeholder="<?php echo ew_HtmlEncode($user_login->login_pwd->getPlaceHolder()) ?>" value="<?php echo $user_login->login_pwd->EditValue ?>"<?php echo $user_login->login_pwd->EditAttributes() ?>>
</span>
<?php echo $user_login->login_pwd->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user_login->grp_user_id->Visible) { // grp_user_id ?>
	<div id="r_grp_user_id" class="form-group">
		<label id="elh_user_login_grp_user_id" for="x_grp_user_id" class="col-sm-2 control-label ewLabel"><?php echo $user_login->grp_user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user_login->grp_user_id->CellAttributes() ?>>
<span id="el_user_login_grp_user_id">
<input type="text" data-table="user_login" data-field="x_grp_user_id" name="x_grp_user_id" id="x_grp_user_id" size="30" placeholder="<?php echo ew_HtmlEncode($user_login->grp_user_id->getPlaceHolder()) ?>" value="<?php echo $user_login->grp_user_id->EditValue ?>"<?php echo $user_login->grp_user_id->EditAttributes() ?>>
</span>
<?php echo $user_login->grp_user_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user_login->lastupdate_date->Visible) { // lastupdate_date ?>
	<div id="r_lastupdate_date" class="form-group">
		<label id="elh_user_login_lastupdate_date" for="x_lastupdate_date" class="col-sm-2 control-label ewLabel"><?php echo $user_login->lastupdate_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user_login->lastupdate_date->CellAttributes() ?>>
<span id="el_user_login_lastupdate_date">
<input type="text" data-table="user_login" data-field="x_lastupdate_date" name="x_lastupdate_date" id="x_lastupdate_date" placeholder="<?php echo ew_HtmlEncode($user_login->lastupdate_date->getPlaceHolder()) ?>" value="<?php echo $user_login->lastupdate_date->EditValue ?>"<?php echo $user_login->lastupdate_date->EditAttributes() ?>>
</span>
<?php echo $user_login->lastupdate_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user_login->lastupdate_user->Visible) { // lastupdate_user ?>
	<div id="r_lastupdate_user" class="form-group">
		<label id="elh_user_login_lastupdate_user" for="x_lastupdate_user" class="col-sm-2 control-label ewLabel"><?php echo $user_login->lastupdate_user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user_login->lastupdate_user->CellAttributes() ?>>
<span id="el_user_login_lastupdate_user">
<input type="text" data-table="user_login" data-field="x_lastupdate_user" name="x_lastupdate_user" id="x_lastupdate_user" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($user_login->lastupdate_user->getPlaceHolder()) ?>" value="<?php echo $user_login->lastupdate_user->EditValue ?>"<?php echo $user_login->lastupdate_user->EditAttributes() ?>>
</span>
<?php echo $user_login->lastupdate_user->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$user_login_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $user_login_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fuser_loginedit.Init();
</script>
<?php
$user_login_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$user_login_edit->Page_Terminate();
?>
