<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "cuti_normatifinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$cuti_normatif_add = NULL; // Initialize page object first

class ccuti_normatif_add extends ccuti_normatif {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'cuti_normatif';

	// Page object name
	var $PageObjName = 'cuti_normatif_add';

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

		// Table object (cuti_normatif)
		if (!isset($GLOBALS["cuti_normatif"]) || get_class($GLOBALS["cuti_normatif"]) == "ccuti_normatif") {
			$GLOBALS["cuti_normatif"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cuti_normatif"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cuti_normatif', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cuti_normatiflist.php"));
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
		$this->cuti_n_id->SetVisibility();
		$this->cuti_n_nama->SetVisibility();
		$this->cuti_n_lama->SetVisibility();
		$this->nominal->SetVisibility();
		$this->jns_bayar->SetVisibility();

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
		global $EW_EXPORT, $cuti_normatif;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cuti_normatif);
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
			if (@$_GET["cuti_n_id"] != "") {
				$this->cuti_n_id->setQueryStringValue($_GET["cuti_n_id"]);
				$this->setKey("cuti_n_id", $this->cuti_n_id->CurrentValue); // Set up key
			} else {
				$this->setKey("cuti_n_id", ""); // Clear key
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
					$this->Page_Terminate("cuti_normatiflist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "cuti_normatiflist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "cuti_normatifview.php")
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
		$this->cuti_n_id->CurrentValue = 0;
		$this->cuti_n_nama->CurrentValue = NULL;
		$this->cuti_n_nama->OldValue = $this->cuti_n_nama->CurrentValue;
		$this->cuti_n_lama->CurrentValue = 0;
		$this->nominal->CurrentValue = 0;
		$this->jns_bayar->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->cuti_n_id->FldIsDetailKey) {
			$this->cuti_n_id->setFormValue($objForm->GetValue("x_cuti_n_id"));
		}
		if (!$this->cuti_n_nama->FldIsDetailKey) {
			$this->cuti_n_nama->setFormValue($objForm->GetValue("x_cuti_n_nama"));
		}
		if (!$this->cuti_n_lama->FldIsDetailKey) {
			$this->cuti_n_lama->setFormValue($objForm->GetValue("x_cuti_n_lama"));
		}
		if (!$this->nominal->FldIsDetailKey) {
			$this->nominal->setFormValue($objForm->GetValue("x_nominal"));
		}
		if (!$this->jns_bayar->FldIsDetailKey) {
			$this->jns_bayar->setFormValue($objForm->GetValue("x_jns_bayar"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->cuti_n_id->CurrentValue = $this->cuti_n_id->FormValue;
		$this->cuti_n_nama->CurrentValue = $this->cuti_n_nama->FormValue;
		$this->cuti_n_lama->CurrentValue = $this->cuti_n_lama->FormValue;
		$this->nominal->CurrentValue = $this->nominal->FormValue;
		$this->jns_bayar->CurrentValue = $this->jns_bayar->FormValue;
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
		$this->cuti_n_id->setDbValue($rs->fields('cuti_n_id'));
		$this->cuti_n_nama->setDbValue($rs->fields('cuti_n_nama'));
		$this->cuti_n_lama->setDbValue($rs->fields('cuti_n_lama'));
		$this->nominal->setDbValue($rs->fields('nominal'));
		$this->jns_bayar->setDbValue($rs->fields('jns_bayar'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->cuti_n_id->DbValue = $row['cuti_n_id'];
		$this->cuti_n_nama->DbValue = $row['cuti_n_nama'];
		$this->cuti_n_lama->DbValue = $row['cuti_n_lama'];
		$this->nominal->DbValue = $row['nominal'];
		$this->jns_bayar->DbValue = $row['jns_bayar'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("cuti_n_id")) <> "")
			$this->cuti_n_id->CurrentValue = $this->getKey("cuti_n_id"); // cuti_n_id
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

		if ($this->nominal->FormValue == $this->nominal->CurrentValue && is_numeric(ew_StrToFloat($this->nominal->CurrentValue)))
			$this->nominal->CurrentValue = ew_StrToFloat($this->nominal->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// cuti_n_id
		// cuti_n_nama
		// cuti_n_lama
		// nominal
		// jns_bayar

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// cuti_n_id
		$this->cuti_n_id->ViewValue = $this->cuti_n_id->CurrentValue;
		$this->cuti_n_id->ViewCustomAttributes = "";

		// cuti_n_nama
		$this->cuti_n_nama->ViewValue = $this->cuti_n_nama->CurrentValue;
		$this->cuti_n_nama->ViewCustomAttributes = "";

		// cuti_n_lama
		$this->cuti_n_lama->ViewValue = $this->cuti_n_lama->CurrentValue;
		$this->cuti_n_lama->ViewCustomAttributes = "";

		// nominal
		$this->nominal->ViewValue = $this->nominal->CurrentValue;
		$this->nominal->ViewCustomAttributes = "";

		// jns_bayar
		$this->jns_bayar->ViewValue = $this->jns_bayar->CurrentValue;
		$this->jns_bayar->ViewCustomAttributes = "";

			// cuti_n_id
			$this->cuti_n_id->LinkCustomAttributes = "";
			$this->cuti_n_id->HrefValue = "";
			$this->cuti_n_id->TooltipValue = "";

			// cuti_n_nama
			$this->cuti_n_nama->LinkCustomAttributes = "";
			$this->cuti_n_nama->HrefValue = "";
			$this->cuti_n_nama->TooltipValue = "";

			// cuti_n_lama
			$this->cuti_n_lama->LinkCustomAttributes = "";
			$this->cuti_n_lama->HrefValue = "";
			$this->cuti_n_lama->TooltipValue = "";

			// nominal
			$this->nominal->LinkCustomAttributes = "";
			$this->nominal->HrefValue = "";
			$this->nominal->TooltipValue = "";

			// jns_bayar
			$this->jns_bayar->LinkCustomAttributes = "";
			$this->jns_bayar->HrefValue = "";
			$this->jns_bayar->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// cuti_n_id
			$this->cuti_n_id->EditAttrs["class"] = "form-control";
			$this->cuti_n_id->EditCustomAttributes = "";
			$this->cuti_n_id->EditValue = ew_HtmlEncode($this->cuti_n_id->CurrentValue);
			$this->cuti_n_id->PlaceHolder = ew_RemoveHtml($this->cuti_n_id->FldCaption());

			// cuti_n_nama
			$this->cuti_n_nama->EditAttrs["class"] = "form-control";
			$this->cuti_n_nama->EditCustomAttributes = "";
			$this->cuti_n_nama->EditValue = ew_HtmlEncode($this->cuti_n_nama->CurrentValue);
			$this->cuti_n_nama->PlaceHolder = ew_RemoveHtml($this->cuti_n_nama->FldCaption());

			// cuti_n_lama
			$this->cuti_n_lama->EditAttrs["class"] = "form-control";
			$this->cuti_n_lama->EditCustomAttributes = "";
			$this->cuti_n_lama->EditValue = ew_HtmlEncode($this->cuti_n_lama->CurrentValue);
			$this->cuti_n_lama->PlaceHolder = ew_RemoveHtml($this->cuti_n_lama->FldCaption());

			// nominal
			$this->nominal->EditAttrs["class"] = "form-control";
			$this->nominal->EditCustomAttributes = "";
			$this->nominal->EditValue = ew_HtmlEncode($this->nominal->CurrentValue);
			$this->nominal->PlaceHolder = ew_RemoveHtml($this->nominal->FldCaption());
			if (strval($this->nominal->EditValue) <> "" && is_numeric($this->nominal->EditValue)) $this->nominal->EditValue = ew_FormatNumber($this->nominal->EditValue, -2, -1, -2, 0);

			// jns_bayar
			$this->jns_bayar->EditAttrs["class"] = "form-control";
			$this->jns_bayar->EditCustomAttributes = "";
			$this->jns_bayar->EditValue = ew_HtmlEncode($this->jns_bayar->CurrentValue);
			$this->jns_bayar->PlaceHolder = ew_RemoveHtml($this->jns_bayar->FldCaption());

			// Add refer script
			// cuti_n_id

			$this->cuti_n_id->LinkCustomAttributes = "";
			$this->cuti_n_id->HrefValue = "";

			// cuti_n_nama
			$this->cuti_n_nama->LinkCustomAttributes = "";
			$this->cuti_n_nama->HrefValue = "";

			// cuti_n_lama
			$this->cuti_n_lama->LinkCustomAttributes = "";
			$this->cuti_n_lama->HrefValue = "";

			// nominal
			$this->nominal->LinkCustomAttributes = "";
			$this->nominal->HrefValue = "";

			// jns_bayar
			$this->jns_bayar->LinkCustomAttributes = "";
			$this->jns_bayar->HrefValue = "";
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
		if (!$this->cuti_n_id->FldIsDetailKey && !is_null($this->cuti_n_id->FormValue) && $this->cuti_n_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cuti_n_id->FldCaption(), $this->cuti_n_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->cuti_n_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->cuti_n_id->FldErrMsg());
		}
		if (!$this->cuti_n_nama->FldIsDetailKey && !is_null($this->cuti_n_nama->FormValue) && $this->cuti_n_nama->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cuti_n_nama->FldCaption(), $this->cuti_n_nama->ReqErrMsg));
		}
		if (!$this->cuti_n_lama->FldIsDetailKey && !is_null($this->cuti_n_lama->FormValue) && $this->cuti_n_lama->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cuti_n_lama->FldCaption(), $this->cuti_n_lama->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->cuti_n_lama->FormValue)) {
			ew_AddMessage($gsFormError, $this->cuti_n_lama->FldErrMsg());
		}
		if (!$this->nominal->FldIsDetailKey && !is_null($this->nominal->FormValue) && $this->nominal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nominal->FldCaption(), $this->nominal->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->nominal->FormValue)) {
			ew_AddMessage($gsFormError, $this->nominal->FldErrMsg());
		}
		if (!$this->jns_bayar->FldIsDetailKey && !is_null($this->jns_bayar->FormValue) && $this->jns_bayar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jns_bayar->FldCaption(), $this->jns_bayar->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jns_bayar->FormValue)) {
			ew_AddMessage($gsFormError, $this->jns_bayar->FldErrMsg());
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

		// cuti_n_id
		$this->cuti_n_id->SetDbValueDef($rsnew, $this->cuti_n_id->CurrentValue, 0, strval($this->cuti_n_id->CurrentValue) == "");

		// cuti_n_nama
		$this->cuti_n_nama->SetDbValueDef($rsnew, $this->cuti_n_nama->CurrentValue, "", FALSE);

		// cuti_n_lama
		$this->cuti_n_lama->SetDbValueDef($rsnew, $this->cuti_n_lama->CurrentValue, 0, strval($this->cuti_n_lama->CurrentValue) == "");

		// nominal
		$this->nominal->SetDbValueDef($rsnew, $this->nominal->CurrentValue, 0, strval($this->nominal->CurrentValue) == "");

		// jns_bayar
		$this->jns_bayar->SetDbValueDef($rsnew, $this->jns_bayar->CurrentValue, 0, strval($this->jns_bayar->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['cuti_n_id']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cuti_normatiflist.php"), "", $this->TableVar, TRUE);
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
if (!isset($cuti_normatif_add)) $cuti_normatif_add = new ccuti_normatif_add();

// Page init
$cuti_normatif_add->Page_Init();

// Page main
$cuti_normatif_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cuti_normatif_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fcuti_normatifadd = new ew_Form("fcuti_normatifadd", "add");

// Validate form
fcuti_normatifadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_cuti_n_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cuti_normatif->cuti_n_id->FldCaption(), $cuti_normatif->cuti_n_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cuti_n_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cuti_normatif->cuti_n_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cuti_n_nama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cuti_normatif->cuti_n_nama->FldCaption(), $cuti_normatif->cuti_n_nama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cuti_n_lama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cuti_normatif->cuti_n_lama->FldCaption(), $cuti_normatif->cuti_n_lama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cuti_n_lama");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cuti_normatif->cuti_n_lama->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nominal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cuti_normatif->nominal->FldCaption(), $cuti_normatif->nominal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nominal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cuti_normatif->nominal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jns_bayar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cuti_normatif->jns_bayar->FldCaption(), $cuti_normatif->jns_bayar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jns_bayar");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cuti_normatif->jns_bayar->FldErrMsg()) ?>");

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
fcuti_normatifadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcuti_normatifadd.ValidateRequired = true;
<?php } else { ?>
fcuti_normatifadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$cuti_normatif_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $cuti_normatif_add->ShowPageHeader(); ?>
<?php
$cuti_normatif_add->ShowMessage();
?>
<form name="fcuti_normatifadd" id="fcuti_normatifadd" class="<?php echo $cuti_normatif_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cuti_normatif_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cuti_normatif_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cuti_normatif">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($cuti_normatif_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($cuti_normatif->cuti_n_id->Visible) { // cuti_n_id ?>
	<div id="r_cuti_n_id" class="form-group">
		<label id="elh_cuti_normatif_cuti_n_id" for="x_cuti_n_id" class="col-sm-2 control-label ewLabel"><?php echo $cuti_normatif->cuti_n_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cuti_normatif->cuti_n_id->CellAttributes() ?>>
<span id="el_cuti_normatif_cuti_n_id">
<input type="text" data-table="cuti_normatif" data-field="x_cuti_n_id" name="x_cuti_n_id" id="x_cuti_n_id" size="30" placeholder="<?php echo ew_HtmlEncode($cuti_normatif->cuti_n_id->getPlaceHolder()) ?>" value="<?php echo $cuti_normatif->cuti_n_id->EditValue ?>"<?php echo $cuti_normatif->cuti_n_id->EditAttributes() ?>>
</span>
<?php echo $cuti_normatif->cuti_n_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cuti_normatif->cuti_n_nama->Visible) { // cuti_n_nama ?>
	<div id="r_cuti_n_nama" class="form-group">
		<label id="elh_cuti_normatif_cuti_n_nama" for="x_cuti_n_nama" class="col-sm-2 control-label ewLabel"><?php echo $cuti_normatif->cuti_n_nama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cuti_normatif->cuti_n_nama->CellAttributes() ?>>
<span id="el_cuti_normatif_cuti_n_nama">
<input type="text" data-table="cuti_normatif" data-field="x_cuti_n_nama" name="x_cuti_n_nama" id="x_cuti_n_nama" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($cuti_normatif->cuti_n_nama->getPlaceHolder()) ?>" value="<?php echo $cuti_normatif->cuti_n_nama->EditValue ?>"<?php echo $cuti_normatif->cuti_n_nama->EditAttributes() ?>>
</span>
<?php echo $cuti_normatif->cuti_n_nama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cuti_normatif->cuti_n_lama->Visible) { // cuti_n_lama ?>
	<div id="r_cuti_n_lama" class="form-group">
		<label id="elh_cuti_normatif_cuti_n_lama" for="x_cuti_n_lama" class="col-sm-2 control-label ewLabel"><?php echo $cuti_normatif->cuti_n_lama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cuti_normatif->cuti_n_lama->CellAttributes() ?>>
<span id="el_cuti_normatif_cuti_n_lama">
<input type="text" data-table="cuti_normatif" data-field="x_cuti_n_lama" name="x_cuti_n_lama" id="x_cuti_n_lama" size="30" placeholder="<?php echo ew_HtmlEncode($cuti_normatif->cuti_n_lama->getPlaceHolder()) ?>" value="<?php echo $cuti_normatif->cuti_n_lama->EditValue ?>"<?php echo $cuti_normatif->cuti_n_lama->EditAttributes() ?>>
</span>
<?php echo $cuti_normatif->cuti_n_lama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cuti_normatif->nominal->Visible) { // nominal ?>
	<div id="r_nominal" class="form-group">
		<label id="elh_cuti_normatif_nominal" for="x_nominal" class="col-sm-2 control-label ewLabel"><?php echo $cuti_normatif->nominal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cuti_normatif->nominal->CellAttributes() ?>>
<span id="el_cuti_normatif_nominal">
<input type="text" data-table="cuti_normatif" data-field="x_nominal" name="x_nominal" id="x_nominal" size="30" placeholder="<?php echo ew_HtmlEncode($cuti_normatif->nominal->getPlaceHolder()) ?>" value="<?php echo $cuti_normatif->nominal->EditValue ?>"<?php echo $cuti_normatif->nominal->EditAttributes() ?>>
</span>
<?php echo $cuti_normatif->nominal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cuti_normatif->jns_bayar->Visible) { // jns_bayar ?>
	<div id="r_jns_bayar" class="form-group">
		<label id="elh_cuti_normatif_jns_bayar" for="x_jns_bayar" class="col-sm-2 control-label ewLabel"><?php echo $cuti_normatif->jns_bayar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $cuti_normatif->jns_bayar->CellAttributes() ?>>
<span id="el_cuti_normatif_jns_bayar">
<input type="text" data-table="cuti_normatif" data-field="x_jns_bayar" name="x_jns_bayar" id="x_jns_bayar" size="30" placeholder="<?php echo ew_HtmlEncode($cuti_normatif->jns_bayar->getPlaceHolder()) ?>" value="<?php echo $cuti_normatif->jns_bayar->EditValue ?>"<?php echo $cuti_normatif->jns_bayar->EditAttributes() ?>>
</span>
<?php echo $cuti_normatif->jns_bayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$cuti_normatif_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $cuti_normatif_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcuti_normatifadd.Init();
</script>
<?php
$cuti_normatif_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cuti_normatif_add->Page_Terminate();
?>