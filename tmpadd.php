<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tmpinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tmp_add = NULL; // Initialize page object first

class ctmp_add extends ctmp {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'tmp';

	// Page object name
	var $PageObjName = 'tmp_add';

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

		// Table object (tmp)
		if (!isset($GLOBALS["tmp"]) || get_class($GLOBALS["tmp"]) == "ctmp") {
			$GLOBALS["tmp"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tmp"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tmp', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("tmplist.php"));
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
		$this->pin->SetVisibility();
		$this->finger_idx->SetVisibility();
		$this->alg_ver->SetVisibility();
		$this->template1->SetVisibility();

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
		global $EW_EXPORT, $tmp;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tmp);
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["pin"] != "") {
				$this->pin->setQueryStringValue($_GET["pin"]);
				$this->setKey("pin", $this->pin->CurrentValue); // Set up key
			} else {
				$this->setKey("pin", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["finger_idx"] != "") {
				$this->finger_idx->setQueryStringValue($_GET["finger_idx"]);
				$this->setKey("finger_idx", $this->finger_idx->CurrentValue); // Set up key
			} else {
				$this->setKey("finger_idx", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["alg_ver"] != "") {
				$this->alg_ver->setQueryStringValue($_GET["alg_ver"]);
				$this->setKey("alg_ver", $this->alg_ver->CurrentValue); // Set up key
			} else {
				$this->setKey("alg_ver", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("tmplist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tmplist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "tmpview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->pin->CurrentValue = NULL;
		$this->pin->OldValue = $this->pin->CurrentValue;
		$this->finger_idx->CurrentValue = NULL;
		$this->finger_idx->OldValue = $this->finger_idx->CurrentValue;
		$this->alg_ver->CurrentValue = NULL;
		$this->alg_ver->OldValue = $this->alg_ver->CurrentValue;
		$this->template1->CurrentValue = NULL;
		$this->template1->OldValue = $this->template1->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->pin->FldIsDetailKey) {
			$this->pin->setFormValue($objForm->GetValue("x_pin"));
		}
		if (!$this->finger_idx->FldIsDetailKey) {
			$this->finger_idx->setFormValue($objForm->GetValue("x_finger_idx"));
		}
		if (!$this->alg_ver->FldIsDetailKey) {
			$this->alg_ver->setFormValue($objForm->GetValue("x_alg_ver"));
		}
		if (!$this->template1->FldIsDetailKey) {
			$this->template1->setFormValue($objForm->GetValue("x_template1"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->pin->CurrentValue = $this->pin->FormValue;
		$this->finger_idx->CurrentValue = $this->finger_idx->FormValue;
		$this->alg_ver->CurrentValue = $this->alg_ver->FormValue;
		$this->template1->CurrentValue = $this->template1->FormValue;
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
		$this->pin->setDbValue($rs->fields('pin'));
		$this->finger_idx->setDbValue($rs->fields('finger_idx'));
		$this->alg_ver->setDbValue($rs->fields('alg_ver'));
		$this->template1->setDbValue($rs->fields('template1'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pin->DbValue = $row['pin'];
		$this->finger_idx->DbValue = $row['finger_idx'];
		$this->alg_ver->DbValue = $row['alg_ver'];
		$this->template1->DbValue = $row['template1'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("pin")) <> "")
			$this->pin->CurrentValue = $this->getKey("pin"); // pin
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("finger_idx")) <> "")
			$this->finger_idx->CurrentValue = $this->getKey("finger_idx"); // finger_idx
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("alg_ver")) <> "")
			$this->alg_ver->CurrentValue = $this->getKey("alg_ver"); // alg_ver
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// pin
		// finger_idx
		// alg_ver
		// template1

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pin
		$this->pin->ViewValue = $this->pin->CurrentValue;
		$this->pin->ViewCustomAttributes = "";

		// finger_idx
		$this->finger_idx->ViewValue = $this->finger_idx->CurrentValue;
		$this->finger_idx->ViewCustomAttributes = "";

		// alg_ver
		$this->alg_ver->ViewValue = $this->alg_ver->CurrentValue;
		$this->alg_ver->ViewCustomAttributes = "";

		// template1
		$this->template1->ViewValue = $this->template1->CurrentValue;
		$this->template1->ViewCustomAttributes = "";

			// pin
			$this->pin->LinkCustomAttributes = "";
			$this->pin->HrefValue = "";
			$this->pin->TooltipValue = "";

			// finger_idx
			$this->finger_idx->LinkCustomAttributes = "";
			$this->finger_idx->HrefValue = "";
			$this->finger_idx->TooltipValue = "";

			// alg_ver
			$this->alg_ver->LinkCustomAttributes = "";
			$this->alg_ver->HrefValue = "";
			$this->alg_ver->TooltipValue = "";

			// template1
			$this->template1->LinkCustomAttributes = "";
			$this->template1->HrefValue = "";
			$this->template1->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// pin
			$this->pin->EditAttrs["class"] = "form-control";
			$this->pin->EditCustomAttributes = "";
			$this->pin->EditValue = ew_HtmlEncode($this->pin->CurrentValue);
			$this->pin->PlaceHolder = ew_RemoveHtml($this->pin->FldCaption());

			// finger_idx
			$this->finger_idx->EditAttrs["class"] = "form-control";
			$this->finger_idx->EditCustomAttributes = "";
			$this->finger_idx->EditValue = ew_HtmlEncode($this->finger_idx->CurrentValue);
			$this->finger_idx->PlaceHolder = ew_RemoveHtml($this->finger_idx->FldCaption());

			// alg_ver
			$this->alg_ver->EditAttrs["class"] = "form-control";
			$this->alg_ver->EditCustomAttributes = "";
			$this->alg_ver->EditValue = ew_HtmlEncode($this->alg_ver->CurrentValue);
			$this->alg_ver->PlaceHolder = ew_RemoveHtml($this->alg_ver->FldCaption());

			// template1
			$this->template1->EditAttrs["class"] = "form-control";
			$this->template1->EditCustomAttributes = "";
			$this->template1->EditValue = ew_HtmlEncode($this->template1->CurrentValue);
			$this->template1->PlaceHolder = ew_RemoveHtml($this->template1->FldCaption());

			// Add refer script
			// pin

			$this->pin->LinkCustomAttributes = "";
			$this->pin->HrefValue = "";

			// finger_idx
			$this->finger_idx->LinkCustomAttributes = "";
			$this->finger_idx->HrefValue = "";

			// alg_ver
			$this->alg_ver->LinkCustomAttributes = "";
			$this->alg_ver->HrefValue = "";

			// template1
			$this->template1->LinkCustomAttributes = "";
			$this->template1->HrefValue = "";
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
		if (!$this->pin->FldIsDetailKey && !is_null($this->pin->FormValue) && $this->pin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pin->FldCaption(), $this->pin->ReqErrMsg));
		}
		if (!$this->finger_idx->FldIsDetailKey && !is_null($this->finger_idx->FormValue) && $this->finger_idx->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->finger_idx->FldCaption(), $this->finger_idx->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->finger_idx->FormValue)) {
			ew_AddMessage($gsFormError, $this->finger_idx->FldErrMsg());
		}
		if (!$this->alg_ver->FldIsDetailKey && !is_null($this->alg_ver->FormValue) && $this->alg_ver->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->alg_ver->FldCaption(), $this->alg_ver->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->alg_ver->FormValue)) {
			ew_AddMessage($gsFormError, $this->alg_ver->FldErrMsg());
		}
		if (!$this->template1->FldIsDetailKey && !is_null($this->template1->FormValue) && $this->template1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->template1->FldCaption(), $this->template1->ReqErrMsg));
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// pin
		$this->pin->SetDbValueDef($rsnew, $this->pin->CurrentValue, "", FALSE);

		// finger_idx
		$this->finger_idx->SetDbValueDef($rsnew, $this->finger_idx->CurrentValue, 0, FALSE);

		// alg_ver
		$this->alg_ver->SetDbValueDef($rsnew, $this->alg_ver->CurrentValue, 0, FALSE);

		// template1
		$this->template1->SetDbValueDef($rsnew, $this->template1->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['pin']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['finger_idx']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['alg_ver']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tmplist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($tmp_add)) $tmp_add = new ctmp_add();

// Page init
$tmp_add->Page_Init();

// Page main
$tmp_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tmp_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ftmpadd = new ew_Form("ftmpadd", "add");

// Validate form
ftmpadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_pin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tmp->pin->FldCaption(), $tmp->pin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_finger_idx");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tmp->finger_idx->FldCaption(), $tmp->finger_idx->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_finger_idx");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tmp->finger_idx->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_alg_ver");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tmp->alg_ver->FldCaption(), $tmp->alg_ver->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_alg_ver");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tmp->alg_ver->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_template1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tmp->template1->FldCaption(), $tmp->template1->ReqErrMsg)) ?>");

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
ftmpadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftmpadd.ValidateRequired = true;
<?php } else { ?>
ftmpadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$tmp_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $tmp_add->ShowPageHeader(); ?>
<?php
$tmp_add->ShowMessage();
?>
<form name="ftmpadd" id="ftmpadd" class="<?php echo $tmp_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tmp_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tmp_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tmp">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($tmp_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($tmp->pin->Visible) { // pin ?>
	<div id="r_pin" class="form-group">
		<label id="elh_tmp_pin" for="x_pin" class="col-sm-2 control-label ewLabel"><?php echo $tmp->pin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tmp->pin->CellAttributes() ?>>
<span id="el_tmp_pin">
<input type="text" data-table="tmp" data-field="x_pin" name="x_pin" id="x_pin" size="30" maxlength="32" placeholder="<?php echo ew_HtmlEncode($tmp->pin->getPlaceHolder()) ?>" value="<?php echo $tmp->pin->EditValue ?>"<?php echo $tmp->pin->EditAttributes() ?>>
</span>
<?php echo $tmp->pin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tmp->finger_idx->Visible) { // finger_idx ?>
	<div id="r_finger_idx" class="form-group">
		<label id="elh_tmp_finger_idx" for="x_finger_idx" class="col-sm-2 control-label ewLabel"><?php echo $tmp->finger_idx->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tmp->finger_idx->CellAttributes() ?>>
<span id="el_tmp_finger_idx">
<input type="text" data-table="tmp" data-field="x_finger_idx" name="x_finger_idx" id="x_finger_idx" size="30" placeholder="<?php echo ew_HtmlEncode($tmp->finger_idx->getPlaceHolder()) ?>" value="<?php echo $tmp->finger_idx->EditValue ?>"<?php echo $tmp->finger_idx->EditAttributes() ?>>
</span>
<?php echo $tmp->finger_idx->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tmp->alg_ver->Visible) { // alg_ver ?>
	<div id="r_alg_ver" class="form-group">
		<label id="elh_tmp_alg_ver" for="x_alg_ver" class="col-sm-2 control-label ewLabel"><?php echo $tmp->alg_ver->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tmp->alg_ver->CellAttributes() ?>>
<span id="el_tmp_alg_ver">
<input type="text" data-table="tmp" data-field="x_alg_ver" name="x_alg_ver" id="x_alg_ver" size="30" placeholder="<?php echo ew_HtmlEncode($tmp->alg_ver->getPlaceHolder()) ?>" value="<?php echo $tmp->alg_ver->EditValue ?>"<?php echo $tmp->alg_ver->EditAttributes() ?>>
</span>
<?php echo $tmp->alg_ver->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tmp->template1->Visible) { // template1 ?>
	<div id="r_template1" class="form-group">
		<label id="elh_tmp_template1" for="x_template1" class="col-sm-2 control-label ewLabel"><?php echo $tmp->template1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tmp->template1->CellAttributes() ?>>
<span id="el_tmp_template1">
<textarea data-table="tmp" data-field="x_template1" name="x_template1" id="x_template1" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($tmp->template1->getPlaceHolder()) ?>"<?php echo $tmp->template1->EditAttributes() ?>><?php echo $tmp->template1->EditValue ?></textarea>
</span>
<?php echo $tmp->template1->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$tmp_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tmp_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftmpadd.Init();
</script>
<?php
$tmp_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tmp_add->Page_Terminate();
?>
