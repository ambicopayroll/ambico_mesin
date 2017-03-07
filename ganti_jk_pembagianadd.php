<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "ganti_jk_pembagianinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$ganti_jk_pembagian_add = NULL; // Initialize page object first

class cganti_jk_pembagian_add extends cganti_jk_pembagian {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'ganti_jk_pembagian';

	// Page object name
	var $PageObjName = 'ganti_jk_pembagian_add';

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

		// Table object (ganti_jk_pembagian)
		if (!isset($GLOBALS["ganti_jk_pembagian"]) || get_class($GLOBALS["ganti_jk_pembagian"]) == "cganti_jk_pembagian") {
			$GLOBALS["ganti_jk_pembagian"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ganti_jk_pembagian"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ganti_jk_pembagian', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("ganti_jk_pembagianlist.php"));
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
		$this->ganti_jk_id->SetVisibility();
		$this->pembagian1_id->SetVisibility();
		$this->pembagian2_id->SetVisibility();
		$this->pembagian3_id->SetVisibility();
		$this->tgl_awal->SetVisibility();
		$this->tgl_akhir->SetVisibility();
		$this->jk_id->SetVisibility();
		$this->keterangan->SetVisibility();

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
		global $EW_EXPORT, $ganti_jk_pembagian;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($ganti_jk_pembagian);
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
			if (@$_GET["ganti_jk_id"] != "") {
				$this->ganti_jk_id->setQueryStringValue($_GET["ganti_jk_id"]);
				$this->setKey("ganti_jk_id", $this->ganti_jk_id->CurrentValue); // Set up key
			} else {
				$this->setKey("ganti_jk_id", ""); // Clear key
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
					$this->Page_Terminate("ganti_jk_pembagianlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "ganti_jk_pembagianlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "ganti_jk_pembagianview.php")
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
		$this->ganti_jk_id->CurrentValue = 0;
		$this->pembagian1_id->CurrentValue = 0;
		$this->pembagian2_id->CurrentValue = 0;
		$this->pembagian3_id->CurrentValue = 0;
		$this->tgl_awal->CurrentValue = "0000-00-00";
		$this->tgl_akhir->CurrentValue = "0000-00-00";
		$this->jk_id->CurrentValue = 0;
		$this->keterangan->CurrentValue = "0";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->ganti_jk_id->FldIsDetailKey) {
			$this->ganti_jk_id->setFormValue($objForm->GetValue("x_ganti_jk_id"));
		}
		if (!$this->pembagian1_id->FldIsDetailKey) {
			$this->pembagian1_id->setFormValue($objForm->GetValue("x_pembagian1_id"));
		}
		if (!$this->pembagian2_id->FldIsDetailKey) {
			$this->pembagian2_id->setFormValue($objForm->GetValue("x_pembagian2_id"));
		}
		if (!$this->pembagian3_id->FldIsDetailKey) {
			$this->pembagian3_id->setFormValue($objForm->GetValue("x_pembagian3_id"));
		}
		if (!$this->tgl_awal->FldIsDetailKey) {
			$this->tgl_awal->setFormValue($objForm->GetValue("x_tgl_awal"));
			$this->tgl_awal->CurrentValue = ew_UnFormatDateTime($this->tgl_awal->CurrentValue, 0);
		}
		if (!$this->tgl_akhir->FldIsDetailKey) {
			$this->tgl_akhir->setFormValue($objForm->GetValue("x_tgl_akhir"));
			$this->tgl_akhir->CurrentValue = ew_UnFormatDateTime($this->tgl_akhir->CurrentValue, 0);
		}
		if (!$this->jk_id->FldIsDetailKey) {
			$this->jk_id->setFormValue($objForm->GetValue("x_jk_id"));
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->ganti_jk_id->CurrentValue = $this->ganti_jk_id->FormValue;
		$this->pembagian1_id->CurrentValue = $this->pembagian1_id->FormValue;
		$this->pembagian2_id->CurrentValue = $this->pembagian2_id->FormValue;
		$this->pembagian3_id->CurrentValue = $this->pembagian3_id->FormValue;
		$this->tgl_awal->CurrentValue = $this->tgl_awal->FormValue;
		$this->tgl_awal->CurrentValue = ew_UnFormatDateTime($this->tgl_awal->CurrentValue, 0);
		$this->tgl_akhir->CurrentValue = $this->tgl_akhir->FormValue;
		$this->tgl_akhir->CurrentValue = ew_UnFormatDateTime($this->tgl_akhir->CurrentValue, 0);
		$this->jk_id->CurrentValue = $this->jk_id->FormValue;
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
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
		$this->ganti_jk_id->setDbValue($rs->fields('ganti_jk_id'));
		$this->pembagian1_id->setDbValue($rs->fields('pembagian1_id'));
		$this->pembagian2_id->setDbValue($rs->fields('pembagian2_id'));
		$this->pembagian3_id->setDbValue($rs->fields('pembagian3_id'));
		$this->tgl_awal->setDbValue($rs->fields('tgl_awal'));
		$this->tgl_akhir->setDbValue($rs->fields('tgl_akhir'));
		$this->jk_id->setDbValue($rs->fields('jk_id'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ganti_jk_id->DbValue = $row['ganti_jk_id'];
		$this->pembagian1_id->DbValue = $row['pembagian1_id'];
		$this->pembagian2_id->DbValue = $row['pembagian2_id'];
		$this->pembagian3_id->DbValue = $row['pembagian3_id'];
		$this->tgl_awal->DbValue = $row['tgl_awal'];
		$this->tgl_akhir->DbValue = $row['tgl_akhir'];
		$this->jk_id->DbValue = $row['jk_id'];
		$this->keterangan->DbValue = $row['keterangan'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("ganti_jk_id")) <> "")
			$this->ganti_jk_id->CurrentValue = $this->getKey("ganti_jk_id"); // ganti_jk_id
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
		// ganti_jk_id
		// pembagian1_id
		// pembagian2_id
		// pembagian3_id
		// tgl_awal
		// tgl_akhir
		// jk_id
		// keterangan

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ganti_jk_id
		$this->ganti_jk_id->ViewValue = $this->ganti_jk_id->CurrentValue;
		$this->ganti_jk_id->ViewCustomAttributes = "";

		// pembagian1_id
		$this->pembagian1_id->ViewValue = $this->pembagian1_id->CurrentValue;
		$this->pembagian1_id->ViewCustomAttributes = "";

		// pembagian2_id
		$this->pembagian2_id->ViewValue = $this->pembagian2_id->CurrentValue;
		$this->pembagian2_id->ViewCustomAttributes = "";

		// pembagian3_id
		$this->pembagian3_id->ViewValue = $this->pembagian3_id->CurrentValue;
		$this->pembagian3_id->ViewCustomAttributes = "";

		// tgl_awal
		$this->tgl_awal->ViewValue = $this->tgl_awal->CurrentValue;
		$this->tgl_awal->ViewValue = ew_FormatDateTime($this->tgl_awal->ViewValue, 0);
		$this->tgl_awal->ViewCustomAttributes = "";

		// tgl_akhir
		$this->tgl_akhir->ViewValue = $this->tgl_akhir->CurrentValue;
		$this->tgl_akhir->ViewValue = ew_FormatDateTime($this->tgl_akhir->ViewValue, 0);
		$this->tgl_akhir->ViewCustomAttributes = "";

		// jk_id
		$this->jk_id->ViewValue = $this->jk_id->CurrentValue;
		$this->jk_id->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

			// ganti_jk_id
			$this->ganti_jk_id->LinkCustomAttributes = "";
			$this->ganti_jk_id->HrefValue = "";
			$this->ganti_jk_id->TooltipValue = "";

			// pembagian1_id
			$this->pembagian1_id->LinkCustomAttributes = "";
			$this->pembagian1_id->HrefValue = "";
			$this->pembagian1_id->TooltipValue = "";

			// pembagian2_id
			$this->pembagian2_id->LinkCustomAttributes = "";
			$this->pembagian2_id->HrefValue = "";
			$this->pembagian2_id->TooltipValue = "";

			// pembagian3_id
			$this->pembagian3_id->LinkCustomAttributes = "";
			$this->pembagian3_id->HrefValue = "";
			$this->pembagian3_id->TooltipValue = "";

			// tgl_awal
			$this->tgl_awal->LinkCustomAttributes = "";
			$this->tgl_awal->HrefValue = "";
			$this->tgl_awal->TooltipValue = "";

			// tgl_akhir
			$this->tgl_akhir->LinkCustomAttributes = "";
			$this->tgl_akhir->HrefValue = "";
			$this->tgl_akhir->TooltipValue = "";

			// jk_id
			$this->jk_id->LinkCustomAttributes = "";
			$this->jk_id->HrefValue = "";
			$this->jk_id->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// ganti_jk_id
			$this->ganti_jk_id->EditAttrs["class"] = "form-control";
			$this->ganti_jk_id->EditCustomAttributes = "";
			$this->ganti_jk_id->EditValue = ew_HtmlEncode($this->ganti_jk_id->CurrentValue);
			$this->ganti_jk_id->PlaceHolder = ew_RemoveHtml($this->ganti_jk_id->FldCaption());

			// pembagian1_id
			$this->pembagian1_id->EditAttrs["class"] = "form-control";
			$this->pembagian1_id->EditCustomAttributes = "";
			$this->pembagian1_id->EditValue = ew_HtmlEncode($this->pembagian1_id->CurrentValue);
			$this->pembagian1_id->PlaceHolder = ew_RemoveHtml($this->pembagian1_id->FldCaption());

			// pembagian2_id
			$this->pembagian2_id->EditAttrs["class"] = "form-control";
			$this->pembagian2_id->EditCustomAttributes = "";
			$this->pembagian2_id->EditValue = ew_HtmlEncode($this->pembagian2_id->CurrentValue);
			$this->pembagian2_id->PlaceHolder = ew_RemoveHtml($this->pembagian2_id->FldCaption());

			// pembagian3_id
			$this->pembagian3_id->EditAttrs["class"] = "form-control";
			$this->pembagian3_id->EditCustomAttributes = "";
			$this->pembagian3_id->EditValue = ew_HtmlEncode($this->pembagian3_id->CurrentValue);
			$this->pembagian3_id->PlaceHolder = ew_RemoveHtml($this->pembagian3_id->FldCaption());

			// tgl_awal
			$this->tgl_awal->EditAttrs["class"] = "form-control";
			$this->tgl_awal->EditCustomAttributes = "";
			$this->tgl_awal->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_awal->CurrentValue, 8));
			$this->tgl_awal->PlaceHolder = ew_RemoveHtml($this->tgl_awal->FldCaption());

			// tgl_akhir
			$this->tgl_akhir->EditAttrs["class"] = "form-control";
			$this->tgl_akhir->EditCustomAttributes = "";
			$this->tgl_akhir->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_akhir->CurrentValue, 8));
			$this->tgl_akhir->PlaceHolder = ew_RemoveHtml($this->tgl_akhir->FldCaption());

			// jk_id
			$this->jk_id->EditAttrs["class"] = "form-control";
			$this->jk_id->EditCustomAttributes = "";
			$this->jk_id->EditValue = ew_HtmlEncode($this->jk_id->CurrentValue);
			$this->jk_id->PlaceHolder = ew_RemoveHtml($this->jk_id->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// Add refer script
			// ganti_jk_id

			$this->ganti_jk_id->LinkCustomAttributes = "";
			$this->ganti_jk_id->HrefValue = "";

			// pembagian1_id
			$this->pembagian1_id->LinkCustomAttributes = "";
			$this->pembagian1_id->HrefValue = "";

			// pembagian2_id
			$this->pembagian2_id->LinkCustomAttributes = "";
			$this->pembagian2_id->HrefValue = "";

			// pembagian3_id
			$this->pembagian3_id->LinkCustomAttributes = "";
			$this->pembagian3_id->HrefValue = "";

			// tgl_awal
			$this->tgl_awal->LinkCustomAttributes = "";
			$this->tgl_awal->HrefValue = "";

			// tgl_akhir
			$this->tgl_akhir->LinkCustomAttributes = "";
			$this->tgl_akhir->HrefValue = "";

			// jk_id
			$this->jk_id->LinkCustomAttributes = "";
			$this->jk_id->HrefValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
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
		if (!$this->ganti_jk_id->FldIsDetailKey && !is_null($this->ganti_jk_id->FormValue) && $this->ganti_jk_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ganti_jk_id->FldCaption(), $this->ganti_jk_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->ganti_jk_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->ganti_jk_id->FldErrMsg());
		}
		if (!$this->pembagian1_id->FldIsDetailKey && !is_null($this->pembagian1_id->FormValue) && $this->pembagian1_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pembagian1_id->FldCaption(), $this->pembagian1_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pembagian1_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pembagian1_id->FldErrMsg());
		}
		if (!$this->pembagian2_id->FldIsDetailKey && !is_null($this->pembagian2_id->FormValue) && $this->pembagian2_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pembagian2_id->FldCaption(), $this->pembagian2_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pembagian2_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pembagian2_id->FldErrMsg());
		}
		if (!$this->pembagian3_id->FldIsDetailKey && !is_null($this->pembagian3_id->FormValue) && $this->pembagian3_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pembagian3_id->FldCaption(), $this->pembagian3_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pembagian3_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pembagian3_id->FldErrMsg());
		}
		if (!$this->tgl_awal->FldIsDetailKey && !is_null($this->tgl_awal->FormValue) && $this->tgl_awal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl_awal->FldCaption(), $this->tgl_awal->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl_awal->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_awal->FldErrMsg());
		}
		if (!$this->tgl_akhir->FldIsDetailKey && !is_null($this->tgl_akhir->FormValue) && $this->tgl_akhir->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl_akhir->FldCaption(), $this->tgl_akhir->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl_akhir->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_akhir->FldErrMsg());
		}
		if (!$this->jk_id->FldIsDetailKey && !is_null($this->jk_id->FormValue) && $this->jk_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_id->FldCaption(), $this->jk_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_id->FldErrMsg());
		}
		if (!$this->keterangan->FldIsDetailKey && !is_null($this->keterangan->FormValue) && $this->keterangan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->keterangan->FldCaption(), $this->keterangan->ReqErrMsg));
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

		// ganti_jk_id
		$this->ganti_jk_id->SetDbValueDef($rsnew, $this->ganti_jk_id->CurrentValue, 0, strval($this->ganti_jk_id->CurrentValue) == "");

		// pembagian1_id
		$this->pembagian1_id->SetDbValueDef($rsnew, $this->pembagian1_id->CurrentValue, 0, strval($this->pembagian1_id->CurrentValue) == "");

		// pembagian2_id
		$this->pembagian2_id->SetDbValueDef($rsnew, $this->pembagian2_id->CurrentValue, 0, strval($this->pembagian2_id->CurrentValue) == "");

		// pembagian3_id
		$this->pembagian3_id->SetDbValueDef($rsnew, $this->pembagian3_id->CurrentValue, 0, strval($this->pembagian3_id->CurrentValue) == "");

		// tgl_awal
		$this->tgl_awal->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_awal->CurrentValue, 0), ew_CurrentDate(), strval($this->tgl_awal->CurrentValue) == "");

		// tgl_akhir
		$this->tgl_akhir->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_akhir->CurrentValue, 0), ew_CurrentDate(), strval($this->tgl_akhir->CurrentValue) == "");

		// jk_id
		$this->jk_id->SetDbValueDef($rsnew, $this->jk_id->CurrentValue, 0, strval($this->jk_id->CurrentValue) == "");

		// keterangan
		$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, "", strval($this->keterangan->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['ganti_jk_id']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("ganti_jk_pembagianlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($ganti_jk_pembagian_add)) $ganti_jk_pembagian_add = new cganti_jk_pembagian_add();

// Page init
$ganti_jk_pembagian_add->Page_Init();

// Page main
$ganti_jk_pembagian_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ganti_jk_pembagian_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fganti_jk_pembagianadd = new ew_Form("fganti_jk_pembagianadd", "add");

// Validate form
fganti_jk_pembagianadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_ganti_jk_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_pembagian->ganti_jk_id->FldCaption(), $ganti_jk_pembagian->ganti_jk_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ganti_jk_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_pembagian->ganti_jk_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pembagian1_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_pembagian->pembagian1_id->FldCaption(), $ganti_jk_pembagian->pembagian1_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pembagian1_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_pembagian->pembagian1_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pembagian2_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_pembagian->pembagian2_id->FldCaption(), $ganti_jk_pembagian->pembagian2_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pembagian2_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_pembagian->pembagian2_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pembagian3_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_pembagian->pembagian3_id->FldCaption(), $ganti_jk_pembagian->pembagian3_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pembagian3_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_pembagian->pembagian3_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_awal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_pembagian->tgl_awal->FldCaption(), $ganti_jk_pembagian->tgl_awal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_awal");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_pembagian->tgl_awal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_akhir");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_pembagian->tgl_akhir->FldCaption(), $ganti_jk_pembagian->tgl_akhir->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_akhir");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_pembagian->tgl_akhir->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_pembagian->jk_id->FldCaption(), $ganti_jk_pembagian->jk_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_pembagian->jk_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_pembagian->keterangan->FldCaption(), $ganti_jk_pembagian->keterangan->ReqErrMsg)) ?>");

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
fganti_jk_pembagianadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fganti_jk_pembagianadd.ValidateRequired = true;
<?php } else { ?>
fganti_jk_pembagianadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$ganti_jk_pembagian_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $ganti_jk_pembagian_add->ShowPageHeader(); ?>
<?php
$ganti_jk_pembagian_add->ShowMessage();
?>
<form name="fganti_jk_pembagianadd" id="fganti_jk_pembagianadd" class="<?php echo $ganti_jk_pembagian_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($ganti_jk_pembagian_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $ganti_jk_pembagian_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="ganti_jk_pembagian">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($ganti_jk_pembagian_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($ganti_jk_pembagian->ganti_jk_id->Visible) { // ganti_jk_id ?>
	<div id="r_ganti_jk_id" class="form-group">
		<label id="elh_ganti_jk_pembagian_ganti_jk_id" for="x_ganti_jk_id" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_pembagian->ganti_jk_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_pembagian->ganti_jk_id->CellAttributes() ?>>
<span id="el_ganti_jk_pembagian_ganti_jk_id">
<input type="text" data-table="ganti_jk_pembagian" data-field="x_ganti_jk_id" name="x_ganti_jk_id" id="x_ganti_jk_id" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jk_pembagian->ganti_jk_id->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_pembagian->ganti_jk_id->EditValue ?>"<?php echo $ganti_jk_pembagian->ganti_jk_id->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_pembagian->ganti_jk_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jk_pembagian->pembagian1_id->Visible) { // pembagian1_id ?>
	<div id="r_pembagian1_id" class="form-group">
		<label id="elh_ganti_jk_pembagian_pembagian1_id" for="x_pembagian1_id" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_pembagian->pembagian1_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_pembagian->pembagian1_id->CellAttributes() ?>>
<span id="el_ganti_jk_pembagian_pembagian1_id">
<input type="text" data-table="ganti_jk_pembagian" data-field="x_pembagian1_id" name="x_pembagian1_id" id="x_pembagian1_id" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jk_pembagian->pembagian1_id->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_pembagian->pembagian1_id->EditValue ?>"<?php echo $ganti_jk_pembagian->pembagian1_id->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_pembagian->pembagian1_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jk_pembagian->pembagian2_id->Visible) { // pembagian2_id ?>
	<div id="r_pembagian2_id" class="form-group">
		<label id="elh_ganti_jk_pembagian_pembagian2_id" for="x_pembagian2_id" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_pembagian->pembagian2_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_pembagian->pembagian2_id->CellAttributes() ?>>
<span id="el_ganti_jk_pembagian_pembagian2_id">
<input type="text" data-table="ganti_jk_pembagian" data-field="x_pembagian2_id" name="x_pembagian2_id" id="x_pembagian2_id" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jk_pembagian->pembagian2_id->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_pembagian->pembagian2_id->EditValue ?>"<?php echo $ganti_jk_pembagian->pembagian2_id->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_pembagian->pembagian2_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jk_pembagian->pembagian3_id->Visible) { // pembagian3_id ?>
	<div id="r_pembagian3_id" class="form-group">
		<label id="elh_ganti_jk_pembagian_pembagian3_id" for="x_pembagian3_id" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_pembagian->pembagian3_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_pembagian->pembagian3_id->CellAttributes() ?>>
<span id="el_ganti_jk_pembagian_pembagian3_id">
<input type="text" data-table="ganti_jk_pembagian" data-field="x_pembagian3_id" name="x_pembagian3_id" id="x_pembagian3_id" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jk_pembagian->pembagian3_id->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_pembagian->pembagian3_id->EditValue ?>"<?php echo $ganti_jk_pembagian->pembagian3_id->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_pembagian->pembagian3_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jk_pembagian->tgl_awal->Visible) { // tgl_awal ?>
	<div id="r_tgl_awal" class="form-group">
		<label id="elh_ganti_jk_pembagian_tgl_awal" for="x_tgl_awal" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_pembagian->tgl_awal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_pembagian->tgl_awal->CellAttributes() ?>>
<span id="el_ganti_jk_pembagian_tgl_awal">
<input type="text" data-table="ganti_jk_pembagian" data-field="x_tgl_awal" name="x_tgl_awal" id="x_tgl_awal" placeholder="<?php echo ew_HtmlEncode($ganti_jk_pembagian->tgl_awal->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_pembagian->tgl_awal->EditValue ?>"<?php echo $ganti_jk_pembagian->tgl_awal->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_pembagian->tgl_awal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jk_pembagian->tgl_akhir->Visible) { // tgl_akhir ?>
	<div id="r_tgl_akhir" class="form-group">
		<label id="elh_ganti_jk_pembagian_tgl_akhir" for="x_tgl_akhir" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_pembagian->tgl_akhir->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_pembagian->tgl_akhir->CellAttributes() ?>>
<span id="el_ganti_jk_pembagian_tgl_akhir">
<input type="text" data-table="ganti_jk_pembagian" data-field="x_tgl_akhir" name="x_tgl_akhir" id="x_tgl_akhir" placeholder="<?php echo ew_HtmlEncode($ganti_jk_pembagian->tgl_akhir->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_pembagian->tgl_akhir->EditValue ?>"<?php echo $ganti_jk_pembagian->tgl_akhir->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_pembagian->tgl_akhir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jk_pembagian->jk_id->Visible) { // jk_id ?>
	<div id="r_jk_id" class="form-group">
		<label id="elh_ganti_jk_pembagian_jk_id" for="x_jk_id" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_pembagian->jk_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_pembagian->jk_id->CellAttributes() ?>>
<span id="el_ganti_jk_pembagian_jk_id">
<input type="text" data-table="ganti_jk_pembagian" data-field="x_jk_id" name="x_jk_id" id="x_jk_id" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jk_pembagian->jk_id->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_pembagian->jk_id->EditValue ?>"<?php echo $ganti_jk_pembagian->jk_id->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_pembagian->jk_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jk_pembagian->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_ganti_jk_pembagian_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_pembagian->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_pembagian->keterangan->CellAttributes() ?>>
<span id="el_ganti_jk_pembagian_keterangan">
<input type="text" data-table="ganti_jk_pembagian" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($ganti_jk_pembagian->keterangan->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_pembagian->keterangan->EditValue ?>"<?php echo $ganti_jk_pembagian->keterangan->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_pembagian->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$ganti_jk_pembagian_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $ganti_jk_pembagian_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fganti_jk_pembagianadd.Init();
</script>
<?php
$ganti_jk_pembagian_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ganti_jk_pembagian_add->Page_Terminate();
?>
