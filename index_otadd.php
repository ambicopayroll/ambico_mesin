<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "index_otinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$index_ot_add = NULL; // Initialize page object first

class cindex_ot_add extends cindex_ot {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'index_ot';

	// Page object name
	var $PageObjName = 'index_ot_add';

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

		// Table object (index_ot)
		if (!isset($GLOBALS["index_ot"]) || get_class($GLOBALS["index_ot"]) == "cindex_ot") {
			$GLOBALS["index_ot"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["index_ot"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'index_ot', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("index_otlist.php"));
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
		$this->index_id->SetVisibility();
		$this->type_ot->SetVisibility();
		$this->from_ot->SetVisibility();
		$this->to_ot->SetVisibility();
		$this->multiplier->SetVisibility();

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
		global $EW_EXPORT, $index_ot;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($index_ot);
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
			if (@$_GET["index_id"] != "") {
				$this->index_id->setQueryStringValue($_GET["index_id"]);
				$this->setKey("index_id", $this->index_id->CurrentValue); // Set up key
			} else {
				$this->setKey("index_id", ""); // Clear key
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
					$this->Page_Terminate("index_otlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "index_otlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "index_otview.php")
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
		$this->index_id->CurrentValue = NULL;
		$this->index_id->OldValue = $this->index_id->CurrentValue;
		$this->type_ot->CurrentValue = 0;
		$this->from_ot->CurrentValue = NULL;
		$this->from_ot->OldValue = $this->from_ot->CurrentValue;
		$this->to_ot->CurrentValue = NULL;
		$this->to_ot->OldValue = $this->to_ot->CurrentValue;
		$this->multiplier->CurrentValue = NULL;
		$this->multiplier->OldValue = $this->multiplier->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->index_id->FldIsDetailKey) {
			$this->index_id->setFormValue($objForm->GetValue("x_index_id"));
		}
		if (!$this->type_ot->FldIsDetailKey) {
			$this->type_ot->setFormValue($objForm->GetValue("x_type_ot"));
		}
		if (!$this->from_ot->FldIsDetailKey) {
			$this->from_ot->setFormValue($objForm->GetValue("x_from_ot"));
		}
		if (!$this->to_ot->FldIsDetailKey) {
			$this->to_ot->setFormValue($objForm->GetValue("x_to_ot"));
		}
		if (!$this->multiplier->FldIsDetailKey) {
			$this->multiplier->setFormValue($objForm->GetValue("x_multiplier"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->index_id->CurrentValue = $this->index_id->FormValue;
		$this->type_ot->CurrentValue = $this->type_ot->FormValue;
		$this->from_ot->CurrentValue = $this->from_ot->FormValue;
		$this->to_ot->CurrentValue = $this->to_ot->FormValue;
		$this->multiplier->CurrentValue = $this->multiplier->FormValue;
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
		$this->index_id->setDbValue($rs->fields('index_id'));
		$this->type_ot->setDbValue($rs->fields('type_ot'));
		$this->from_ot->setDbValue($rs->fields('from_ot'));
		$this->to_ot->setDbValue($rs->fields('to_ot'));
		$this->multiplier->setDbValue($rs->fields('multiplier'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->index_id->DbValue = $row['index_id'];
		$this->type_ot->DbValue = $row['type_ot'];
		$this->from_ot->DbValue = $row['from_ot'];
		$this->to_ot->DbValue = $row['to_ot'];
		$this->multiplier->DbValue = $row['multiplier'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("index_id")) <> "")
			$this->index_id->CurrentValue = $this->getKey("index_id"); // index_id
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
		// Convert decimal values if posted back

		if ($this->multiplier->FormValue == $this->multiplier->CurrentValue && is_numeric(ew_StrToFloat($this->multiplier->CurrentValue)))
			$this->multiplier->CurrentValue = ew_StrToFloat($this->multiplier->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// index_id
		// type_ot
		// from_ot
		// to_ot
		// multiplier

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// index_id
		$this->index_id->ViewValue = $this->index_id->CurrentValue;
		$this->index_id->ViewCustomAttributes = "";

		// type_ot
		$this->type_ot->ViewValue = $this->type_ot->CurrentValue;
		$this->type_ot->ViewCustomAttributes = "";

		// from_ot
		$this->from_ot->ViewValue = $this->from_ot->CurrentValue;
		$this->from_ot->ViewCustomAttributes = "";

		// to_ot
		$this->to_ot->ViewValue = $this->to_ot->CurrentValue;
		$this->to_ot->ViewCustomAttributes = "";

		// multiplier
		$this->multiplier->ViewValue = $this->multiplier->CurrentValue;
		$this->multiplier->ViewCustomAttributes = "";

			// index_id
			$this->index_id->LinkCustomAttributes = "";
			$this->index_id->HrefValue = "";
			$this->index_id->TooltipValue = "";

			// type_ot
			$this->type_ot->LinkCustomAttributes = "";
			$this->type_ot->HrefValue = "";
			$this->type_ot->TooltipValue = "";

			// from_ot
			$this->from_ot->LinkCustomAttributes = "";
			$this->from_ot->HrefValue = "";
			$this->from_ot->TooltipValue = "";

			// to_ot
			$this->to_ot->LinkCustomAttributes = "";
			$this->to_ot->HrefValue = "";
			$this->to_ot->TooltipValue = "";

			// multiplier
			$this->multiplier->LinkCustomAttributes = "";
			$this->multiplier->HrefValue = "";
			$this->multiplier->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// index_id
			$this->index_id->EditAttrs["class"] = "form-control";
			$this->index_id->EditCustomAttributes = "";
			$this->index_id->EditValue = ew_HtmlEncode($this->index_id->CurrentValue);
			$this->index_id->PlaceHolder = ew_RemoveHtml($this->index_id->FldCaption());

			// type_ot
			$this->type_ot->EditAttrs["class"] = "form-control";
			$this->type_ot->EditCustomAttributes = "";
			$this->type_ot->EditValue = ew_HtmlEncode($this->type_ot->CurrentValue);
			$this->type_ot->PlaceHolder = ew_RemoveHtml($this->type_ot->FldCaption());

			// from_ot
			$this->from_ot->EditAttrs["class"] = "form-control";
			$this->from_ot->EditCustomAttributes = "";
			$this->from_ot->EditValue = ew_HtmlEncode($this->from_ot->CurrentValue);
			$this->from_ot->PlaceHolder = ew_RemoveHtml($this->from_ot->FldCaption());

			// to_ot
			$this->to_ot->EditAttrs["class"] = "form-control";
			$this->to_ot->EditCustomAttributes = "";
			$this->to_ot->EditValue = ew_HtmlEncode($this->to_ot->CurrentValue);
			$this->to_ot->PlaceHolder = ew_RemoveHtml($this->to_ot->FldCaption());

			// multiplier
			$this->multiplier->EditAttrs["class"] = "form-control";
			$this->multiplier->EditCustomAttributes = "";
			$this->multiplier->EditValue = ew_HtmlEncode($this->multiplier->CurrentValue);
			$this->multiplier->PlaceHolder = ew_RemoveHtml($this->multiplier->FldCaption());
			if (strval($this->multiplier->EditValue) <> "" && is_numeric($this->multiplier->EditValue)) $this->multiplier->EditValue = ew_FormatNumber($this->multiplier->EditValue, -2, -1, -2, 0);

			// Add refer script
			// index_id

			$this->index_id->LinkCustomAttributes = "";
			$this->index_id->HrefValue = "";

			// type_ot
			$this->type_ot->LinkCustomAttributes = "";
			$this->type_ot->HrefValue = "";

			// from_ot
			$this->from_ot->LinkCustomAttributes = "";
			$this->from_ot->HrefValue = "";

			// to_ot
			$this->to_ot->LinkCustomAttributes = "";
			$this->to_ot->HrefValue = "";

			// multiplier
			$this->multiplier->LinkCustomAttributes = "";
			$this->multiplier->HrefValue = "";
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
		if (!$this->index_id->FldIsDetailKey && !is_null($this->index_id->FormValue) && $this->index_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->index_id->FldCaption(), $this->index_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->index_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->index_id->FldErrMsg());
		}
		if (!$this->type_ot->FldIsDetailKey && !is_null($this->type_ot->FormValue) && $this->type_ot->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->type_ot->FldCaption(), $this->type_ot->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->type_ot->FormValue)) {
			ew_AddMessage($gsFormError, $this->type_ot->FldErrMsg());
		}
		if (!$this->from_ot->FldIsDetailKey && !is_null($this->from_ot->FormValue) && $this->from_ot->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->from_ot->FldCaption(), $this->from_ot->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->from_ot->FormValue)) {
			ew_AddMessage($gsFormError, $this->from_ot->FldErrMsg());
		}
		if (!$this->to_ot->FldIsDetailKey && !is_null($this->to_ot->FormValue) && $this->to_ot->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->to_ot->FldCaption(), $this->to_ot->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->to_ot->FormValue)) {
			ew_AddMessage($gsFormError, $this->to_ot->FldErrMsg());
		}
		if (!$this->multiplier->FldIsDetailKey && !is_null($this->multiplier->FormValue) && $this->multiplier->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->multiplier->FldCaption(), $this->multiplier->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->multiplier->FormValue)) {
			ew_AddMessage($gsFormError, $this->multiplier->FldErrMsg());
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

		// index_id
		$this->index_id->SetDbValueDef($rsnew, $this->index_id->CurrentValue, 0, FALSE);

		// type_ot
		$this->type_ot->SetDbValueDef($rsnew, $this->type_ot->CurrentValue, 0, strval($this->type_ot->CurrentValue) == "");

		// from_ot
		$this->from_ot->SetDbValueDef($rsnew, $this->from_ot->CurrentValue, 0, FALSE);

		// to_ot
		$this->to_ot->SetDbValueDef($rsnew, $this->to_ot->CurrentValue, 0, FALSE);

		// multiplier
		$this->multiplier->SetDbValueDef($rsnew, $this->multiplier->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['index_id']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("index_otlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($index_ot_add)) $index_ot_add = new cindex_ot_add();

// Page init
$index_ot_add->Page_Init();

// Page main
$index_ot_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$index_ot_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = findex_otadd = new ew_Form("findex_otadd", "add");

// Validate form
findex_otadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_index_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $index_ot->index_id->FldCaption(), $index_ot->index_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_index_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($index_ot->index_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_type_ot");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $index_ot->type_ot->FldCaption(), $index_ot->type_ot->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_type_ot");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($index_ot->type_ot->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_from_ot");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $index_ot->from_ot->FldCaption(), $index_ot->from_ot->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_from_ot");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($index_ot->from_ot->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_to_ot");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $index_ot->to_ot->FldCaption(), $index_ot->to_ot->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_to_ot");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($index_ot->to_ot->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_multiplier");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $index_ot->multiplier->FldCaption(), $index_ot->multiplier->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_multiplier");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($index_ot->multiplier->FldErrMsg()) ?>");

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
findex_otadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
findex_otadd.ValidateRequired = true;
<?php } else { ?>
findex_otadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$index_ot_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $index_ot_add->ShowPageHeader(); ?>
<?php
$index_ot_add->ShowMessage();
?>
<form name="findex_otadd" id="findex_otadd" class="<?php echo $index_ot_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($index_ot_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $index_ot_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="index_ot">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($index_ot_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($index_ot->index_id->Visible) { // index_id ?>
	<div id="r_index_id" class="form-group">
		<label id="elh_index_ot_index_id" for="x_index_id" class="col-sm-2 control-label ewLabel"><?php echo $index_ot->index_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $index_ot->index_id->CellAttributes() ?>>
<span id="el_index_ot_index_id">
<input type="text" data-table="index_ot" data-field="x_index_id" name="x_index_id" id="x_index_id" size="30" placeholder="<?php echo ew_HtmlEncode($index_ot->index_id->getPlaceHolder()) ?>" value="<?php echo $index_ot->index_id->EditValue ?>"<?php echo $index_ot->index_id->EditAttributes() ?>>
</span>
<?php echo $index_ot->index_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($index_ot->type_ot->Visible) { // type_ot ?>
	<div id="r_type_ot" class="form-group">
		<label id="elh_index_ot_type_ot" for="x_type_ot" class="col-sm-2 control-label ewLabel"><?php echo $index_ot->type_ot->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $index_ot->type_ot->CellAttributes() ?>>
<span id="el_index_ot_type_ot">
<input type="text" data-table="index_ot" data-field="x_type_ot" name="x_type_ot" id="x_type_ot" size="30" placeholder="<?php echo ew_HtmlEncode($index_ot->type_ot->getPlaceHolder()) ?>" value="<?php echo $index_ot->type_ot->EditValue ?>"<?php echo $index_ot->type_ot->EditAttributes() ?>>
</span>
<?php echo $index_ot->type_ot->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($index_ot->from_ot->Visible) { // from_ot ?>
	<div id="r_from_ot" class="form-group">
		<label id="elh_index_ot_from_ot" for="x_from_ot" class="col-sm-2 control-label ewLabel"><?php echo $index_ot->from_ot->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $index_ot->from_ot->CellAttributes() ?>>
<span id="el_index_ot_from_ot">
<input type="text" data-table="index_ot" data-field="x_from_ot" name="x_from_ot" id="x_from_ot" size="30" placeholder="<?php echo ew_HtmlEncode($index_ot->from_ot->getPlaceHolder()) ?>" value="<?php echo $index_ot->from_ot->EditValue ?>"<?php echo $index_ot->from_ot->EditAttributes() ?>>
</span>
<?php echo $index_ot->from_ot->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($index_ot->to_ot->Visible) { // to_ot ?>
	<div id="r_to_ot" class="form-group">
		<label id="elh_index_ot_to_ot" for="x_to_ot" class="col-sm-2 control-label ewLabel"><?php echo $index_ot->to_ot->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $index_ot->to_ot->CellAttributes() ?>>
<span id="el_index_ot_to_ot">
<input type="text" data-table="index_ot" data-field="x_to_ot" name="x_to_ot" id="x_to_ot" size="30" placeholder="<?php echo ew_HtmlEncode($index_ot->to_ot->getPlaceHolder()) ?>" value="<?php echo $index_ot->to_ot->EditValue ?>"<?php echo $index_ot->to_ot->EditAttributes() ?>>
</span>
<?php echo $index_ot->to_ot->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($index_ot->multiplier->Visible) { // multiplier ?>
	<div id="r_multiplier" class="form-group">
		<label id="elh_index_ot_multiplier" for="x_multiplier" class="col-sm-2 control-label ewLabel"><?php echo $index_ot->multiplier->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $index_ot->multiplier->CellAttributes() ?>>
<span id="el_index_ot_multiplier">
<input type="text" data-table="index_ot" data-field="x_multiplier" name="x_multiplier" id="x_multiplier" size="30" placeholder="<?php echo ew_HtmlEncode($index_ot->multiplier->getPlaceHolder()) ?>" value="<?php echo $index_ot->multiplier->EditValue ?>"<?php echo $index_ot->multiplier->EditAttributes() ?>>
</span>
<?php echo $index_ot->multiplier->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$index_ot_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $index_ot_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
findex_otadd.Init();
</script>
<?php
$index_ot_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$index_ot_add->Page_Terminate();
?>
