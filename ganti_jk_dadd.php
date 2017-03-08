<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "ganti_jk_dinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$ganti_jk_d_add = NULL; // Initialize page object first

class cganti_jk_d_add extends cganti_jk_d {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'ganti_jk_d';

	// Page object name
	var $PageObjName = 'ganti_jk_d_add';

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

		// Table object (ganti_jk_d)
		if (!isset($GLOBALS["ganti_jk_d"]) || get_class($GLOBALS["ganti_jk_d"]) == "cganti_jk_d") {
			$GLOBALS["ganti_jk_d"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ganti_jk_d"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ganti_jk_d', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("ganti_jk_dlist.php"));
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
		$this->tgl_ganti_jk->SetVisibility();
		$this->jns_ganti_jk->SetVisibility();
		$this->jk_id->SetVisibility();
		$this->pegawai_id->SetVisibility();
		$this->libur->SetVisibility();

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
		global $EW_EXPORT, $ganti_jk_d;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($ganti_jk_d);
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
			if (@$_GET["tgl_ganti_jk"] != "") {
				$this->tgl_ganti_jk->setQueryStringValue($_GET["tgl_ganti_jk"]);
				$this->setKey("tgl_ganti_jk", $this->tgl_ganti_jk->CurrentValue); // Set up key
			} else {
				$this->setKey("tgl_ganti_jk", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["jns_ganti_jk"] != "") {
				$this->jns_ganti_jk->setQueryStringValue($_GET["jns_ganti_jk"]);
				$this->setKey("jns_ganti_jk", $this->jns_ganti_jk->CurrentValue); // Set up key
			} else {
				$this->setKey("jns_ganti_jk", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["jk_id"] != "") {
				$this->jk_id->setQueryStringValue($_GET["jk_id"]);
				$this->setKey("jk_id", $this->jk_id->CurrentValue); // Set up key
			} else {
				$this->setKey("jk_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["pegawai_id"] != "") {
				$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
				$this->setKey("pegawai_id", $this->pegawai_id->CurrentValue); // Set up key
			} else {
				$this->setKey("pegawai_id", ""); // Clear key
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
					$this->Page_Terminate("ganti_jk_dlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "ganti_jk_dlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "ganti_jk_dview.php")
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
		$this->ganti_jk_id->CurrentValue = NULL;
		$this->ganti_jk_id->OldValue = $this->ganti_jk_id->CurrentValue;
		$this->tgl_ganti_jk->CurrentValue = NULL;
		$this->tgl_ganti_jk->OldValue = $this->tgl_ganti_jk->CurrentValue;
		$this->jns_ganti_jk->CurrentValue = NULL;
		$this->jns_ganti_jk->OldValue = $this->jns_ganti_jk->CurrentValue;
		$this->jk_id->CurrentValue = NULL;
		$this->jk_id->OldValue = $this->jk_id->CurrentValue;
		$this->pegawai_id->CurrentValue = 0;
		$this->libur->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->ganti_jk_id->FldIsDetailKey) {
			$this->ganti_jk_id->setFormValue($objForm->GetValue("x_ganti_jk_id"));
		}
		if (!$this->tgl_ganti_jk->FldIsDetailKey) {
			$this->tgl_ganti_jk->setFormValue($objForm->GetValue("x_tgl_ganti_jk"));
			$this->tgl_ganti_jk->CurrentValue = ew_UnFormatDateTime($this->tgl_ganti_jk->CurrentValue, 0);
		}
		if (!$this->jns_ganti_jk->FldIsDetailKey) {
			$this->jns_ganti_jk->setFormValue($objForm->GetValue("x_jns_ganti_jk"));
		}
		if (!$this->jk_id->FldIsDetailKey) {
			$this->jk_id->setFormValue($objForm->GetValue("x_jk_id"));
		}
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
		if (!$this->libur->FldIsDetailKey) {
			$this->libur->setFormValue($objForm->GetValue("x_libur"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->ganti_jk_id->CurrentValue = $this->ganti_jk_id->FormValue;
		$this->tgl_ganti_jk->CurrentValue = $this->tgl_ganti_jk->FormValue;
		$this->tgl_ganti_jk->CurrentValue = ew_UnFormatDateTime($this->tgl_ganti_jk->CurrentValue, 0);
		$this->jns_ganti_jk->CurrentValue = $this->jns_ganti_jk->FormValue;
		$this->jk_id->CurrentValue = $this->jk_id->FormValue;
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->libur->CurrentValue = $this->libur->FormValue;
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
		$this->tgl_ganti_jk->setDbValue($rs->fields('tgl_ganti_jk'));
		$this->jns_ganti_jk->setDbValue($rs->fields('jns_ganti_jk'));
		$this->jk_id->setDbValue($rs->fields('jk_id'));
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		$this->libur->setDbValue($rs->fields('libur'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ganti_jk_id->DbValue = $row['ganti_jk_id'];
		$this->tgl_ganti_jk->DbValue = $row['tgl_ganti_jk'];
		$this->jns_ganti_jk->DbValue = $row['jns_ganti_jk'];
		$this->jk_id->DbValue = $row['jk_id'];
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->libur->DbValue = $row['libur'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("ganti_jk_id")) <> "")
			$this->ganti_jk_id->CurrentValue = $this->getKey("ganti_jk_id"); // ganti_jk_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("tgl_ganti_jk")) <> "")
			$this->tgl_ganti_jk->CurrentValue = $this->getKey("tgl_ganti_jk"); // tgl_ganti_jk
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("jns_ganti_jk")) <> "")
			$this->jns_ganti_jk->CurrentValue = $this->getKey("jns_ganti_jk"); // jns_ganti_jk
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("jk_id")) <> "")
			$this->jk_id->CurrentValue = $this->getKey("jk_id"); // jk_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("pegawai_id")) <> "")
			$this->pegawai_id->CurrentValue = $this->getKey("pegawai_id"); // pegawai_id
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
		// tgl_ganti_jk
		// jns_ganti_jk
		// jk_id
		// pegawai_id
		// libur

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ganti_jk_id
		$this->ganti_jk_id->ViewValue = $this->ganti_jk_id->CurrentValue;
		$this->ganti_jk_id->ViewCustomAttributes = "";

		// tgl_ganti_jk
		$this->tgl_ganti_jk->ViewValue = $this->tgl_ganti_jk->CurrentValue;
		$this->tgl_ganti_jk->ViewValue = ew_FormatDateTime($this->tgl_ganti_jk->ViewValue, 0);
		$this->tgl_ganti_jk->ViewCustomAttributes = "";

		// jns_ganti_jk
		$this->jns_ganti_jk->ViewValue = $this->jns_ganti_jk->CurrentValue;
		$this->jns_ganti_jk->ViewCustomAttributes = "";

		// jk_id
		$this->jk_id->ViewValue = $this->jk_id->CurrentValue;
		$this->jk_id->ViewCustomAttributes = "";

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// libur
		$this->libur->ViewValue = $this->libur->CurrentValue;
		$this->libur->ViewCustomAttributes = "";

			// ganti_jk_id
			$this->ganti_jk_id->LinkCustomAttributes = "";
			$this->ganti_jk_id->HrefValue = "";
			$this->ganti_jk_id->TooltipValue = "";

			// tgl_ganti_jk
			$this->tgl_ganti_jk->LinkCustomAttributes = "";
			$this->tgl_ganti_jk->HrefValue = "";
			$this->tgl_ganti_jk->TooltipValue = "";

			// jns_ganti_jk
			$this->jns_ganti_jk->LinkCustomAttributes = "";
			$this->jns_ganti_jk->HrefValue = "";
			$this->jns_ganti_jk->TooltipValue = "";

			// jk_id
			$this->jk_id->LinkCustomAttributes = "";
			$this->jk_id->HrefValue = "";
			$this->jk_id->TooltipValue = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

			// libur
			$this->libur->LinkCustomAttributes = "";
			$this->libur->HrefValue = "";
			$this->libur->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// ganti_jk_id
			$this->ganti_jk_id->EditAttrs["class"] = "form-control";
			$this->ganti_jk_id->EditCustomAttributes = "";
			$this->ganti_jk_id->EditValue = ew_HtmlEncode($this->ganti_jk_id->CurrentValue);
			$this->ganti_jk_id->PlaceHolder = ew_RemoveHtml($this->ganti_jk_id->FldCaption());

			// tgl_ganti_jk
			$this->tgl_ganti_jk->EditAttrs["class"] = "form-control";
			$this->tgl_ganti_jk->EditCustomAttributes = "";
			$this->tgl_ganti_jk->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_ganti_jk->CurrentValue, 8));
			$this->tgl_ganti_jk->PlaceHolder = ew_RemoveHtml($this->tgl_ganti_jk->FldCaption());

			// jns_ganti_jk
			$this->jns_ganti_jk->EditAttrs["class"] = "form-control";
			$this->jns_ganti_jk->EditCustomAttributes = "";
			$this->jns_ganti_jk->EditValue = ew_HtmlEncode($this->jns_ganti_jk->CurrentValue);
			$this->jns_ganti_jk->PlaceHolder = ew_RemoveHtml($this->jns_ganti_jk->FldCaption());

			// jk_id
			$this->jk_id->EditAttrs["class"] = "form-control";
			$this->jk_id->EditCustomAttributes = "";
			$this->jk_id->EditValue = ew_HtmlEncode($this->jk_id->CurrentValue);
			$this->jk_id->PlaceHolder = ew_RemoveHtml($this->jk_id->FldCaption());

			// pegawai_id
			$this->pegawai_id->EditAttrs["class"] = "form-control";
			$this->pegawai_id->EditCustomAttributes = "";
			$this->pegawai_id->EditValue = ew_HtmlEncode($this->pegawai_id->CurrentValue);
			$this->pegawai_id->PlaceHolder = ew_RemoveHtml($this->pegawai_id->FldCaption());

			// libur
			$this->libur->EditAttrs["class"] = "form-control";
			$this->libur->EditCustomAttributes = "";
			$this->libur->EditValue = ew_HtmlEncode($this->libur->CurrentValue);
			$this->libur->PlaceHolder = ew_RemoveHtml($this->libur->FldCaption());

			// Add refer script
			// ganti_jk_id

			$this->ganti_jk_id->LinkCustomAttributes = "";
			$this->ganti_jk_id->HrefValue = "";

			// tgl_ganti_jk
			$this->tgl_ganti_jk->LinkCustomAttributes = "";
			$this->tgl_ganti_jk->HrefValue = "";

			// jns_ganti_jk
			$this->jns_ganti_jk->LinkCustomAttributes = "";
			$this->jns_ganti_jk->HrefValue = "";

			// jk_id
			$this->jk_id->LinkCustomAttributes = "";
			$this->jk_id->HrefValue = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";

			// libur
			$this->libur->LinkCustomAttributes = "";
			$this->libur->HrefValue = "";
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
		if (!$this->tgl_ganti_jk->FldIsDetailKey && !is_null($this->tgl_ganti_jk->FormValue) && $this->tgl_ganti_jk->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl_ganti_jk->FldCaption(), $this->tgl_ganti_jk->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl_ganti_jk->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_ganti_jk->FldErrMsg());
		}
		if (!$this->jns_ganti_jk->FldIsDetailKey && !is_null($this->jns_ganti_jk->FormValue) && $this->jns_ganti_jk->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jns_ganti_jk->FldCaption(), $this->jns_ganti_jk->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jns_ganti_jk->FormValue)) {
			ew_AddMessage($gsFormError, $this->jns_ganti_jk->FldErrMsg());
		}
		if (!$this->jk_id->FldIsDetailKey && !is_null($this->jk_id->FormValue) && $this->jk_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_id->FldCaption(), $this->jk_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_id->FldErrMsg());
		}
		if (!$this->pegawai_id->FldIsDetailKey && !is_null($this->pegawai_id->FormValue) && $this->pegawai_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_id->FldCaption(), $this->pegawai_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pegawai_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pegawai_id->FldErrMsg());
		}
		if (!$this->libur->FldIsDetailKey && !is_null($this->libur->FormValue) && $this->libur->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->libur->FldCaption(), $this->libur->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->libur->FormValue)) {
			ew_AddMessage($gsFormError, $this->libur->FldErrMsg());
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
		$this->ganti_jk_id->SetDbValueDef($rsnew, $this->ganti_jk_id->CurrentValue, 0, FALSE);

		// tgl_ganti_jk
		$this->tgl_ganti_jk->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_ganti_jk->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// jns_ganti_jk
		$this->jns_ganti_jk->SetDbValueDef($rsnew, $this->jns_ganti_jk->CurrentValue, 0, FALSE);

		// jk_id
		$this->jk_id->SetDbValueDef($rsnew, $this->jk_id->CurrentValue, 0, FALSE);

		// pegawai_id
		$this->pegawai_id->SetDbValueDef($rsnew, $this->pegawai_id->CurrentValue, 0, strval($this->pegawai_id->CurrentValue) == "");

		// libur
		$this->libur->SetDbValueDef($rsnew, $this->libur->CurrentValue, 0, strval($this->libur->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['ganti_jk_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['tgl_ganti_jk']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['jns_ganti_jk']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['jk_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['pegawai_id']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("ganti_jk_dlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($ganti_jk_d_add)) $ganti_jk_d_add = new cganti_jk_d_add();

// Page init
$ganti_jk_d_add->Page_Init();

// Page main
$ganti_jk_d_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ganti_jk_d_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fganti_jk_dadd = new ew_Form("fganti_jk_dadd", "add");

// Validate form
fganti_jk_dadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_d->ganti_jk_id->FldCaption(), $ganti_jk_d->ganti_jk_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ganti_jk_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_d->ganti_jk_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_ganti_jk");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_d->tgl_ganti_jk->FldCaption(), $ganti_jk_d->tgl_ganti_jk->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_ganti_jk");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_d->tgl_ganti_jk->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jns_ganti_jk");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_d->jns_ganti_jk->FldCaption(), $ganti_jk_d->jns_ganti_jk->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jns_ganti_jk");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_d->jns_ganti_jk->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_d->jk_id->FldCaption(), $ganti_jk_d->jk_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_d->jk_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_d->pegawai_id->FldCaption(), $ganti_jk_d->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_d->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_libur");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jk_d->libur->FldCaption(), $ganti_jk_d->libur->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_libur");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jk_d->libur->FldErrMsg()) ?>");

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
fganti_jk_dadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fganti_jk_dadd.ValidateRequired = true;
<?php } else { ?>
fganti_jk_dadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$ganti_jk_d_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $ganti_jk_d_add->ShowPageHeader(); ?>
<?php
$ganti_jk_d_add->ShowMessage();
?>
<form name="fganti_jk_dadd" id="fganti_jk_dadd" class="<?php echo $ganti_jk_d_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($ganti_jk_d_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $ganti_jk_d_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="ganti_jk_d">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($ganti_jk_d_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($ganti_jk_d->ganti_jk_id->Visible) { // ganti_jk_id ?>
	<div id="r_ganti_jk_id" class="form-group">
		<label id="elh_ganti_jk_d_ganti_jk_id" for="x_ganti_jk_id" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_d->ganti_jk_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_d->ganti_jk_id->CellAttributes() ?>>
<span id="el_ganti_jk_d_ganti_jk_id">
<input type="text" data-table="ganti_jk_d" data-field="x_ganti_jk_id" name="x_ganti_jk_id" id="x_ganti_jk_id" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jk_d->ganti_jk_id->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_d->ganti_jk_id->EditValue ?>"<?php echo $ganti_jk_d->ganti_jk_id->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_d->ganti_jk_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jk_d->tgl_ganti_jk->Visible) { // tgl_ganti_jk ?>
	<div id="r_tgl_ganti_jk" class="form-group">
		<label id="elh_ganti_jk_d_tgl_ganti_jk" for="x_tgl_ganti_jk" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_d->tgl_ganti_jk->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_d->tgl_ganti_jk->CellAttributes() ?>>
<span id="el_ganti_jk_d_tgl_ganti_jk">
<input type="text" data-table="ganti_jk_d" data-field="x_tgl_ganti_jk" name="x_tgl_ganti_jk" id="x_tgl_ganti_jk" placeholder="<?php echo ew_HtmlEncode($ganti_jk_d->tgl_ganti_jk->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_d->tgl_ganti_jk->EditValue ?>"<?php echo $ganti_jk_d->tgl_ganti_jk->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_d->tgl_ganti_jk->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jk_d->jns_ganti_jk->Visible) { // jns_ganti_jk ?>
	<div id="r_jns_ganti_jk" class="form-group">
		<label id="elh_ganti_jk_d_jns_ganti_jk" for="x_jns_ganti_jk" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_d->jns_ganti_jk->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_d->jns_ganti_jk->CellAttributes() ?>>
<span id="el_ganti_jk_d_jns_ganti_jk">
<input type="text" data-table="ganti_jk_d" data-field="x_jns_ganti_jk" name="x_jns_ganti_jk" id="x_jns_ganti_jk" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jk_d->jns_ganti_jk->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_d->jns_ganti_jk->EditValue ?>"<?php echo $ganti_jk_d->jns_ganti_jk->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_d->jns_ganti_jk->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jk_d->jk_id->Visible) { // jk_id ?>
	<div id="r_jk_id" class="form-group">
		<label id="elh_ganti_jk_d_jk_id" for="x_jk_id" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_d->jk_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_d->jk_id->CellAttributes() ?>>
<span id="el_ganti_jk_d_jk_id">
<input type="text" data-table="ganti_jk_d" data-field="x_jk_id" name="x_jk_id" id="x_jk_id" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jk_d->jk_id->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_d->jk_id->EditValue ?>"<?php echo $ganti_jk_d->jk_id->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_d->jk_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jk_d->pegawai_id->Visible) { // pegawai_id ?>
	<div id="r_pegawai_id" class="form-group">
		<label id="elh_ganti_jk_d_pegawai_id" for="x_pegawai_id" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_d->pegawai_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_d->pegawai_id->CellAttributes() ?>>
<span id="el_ganti_jk_d_pegawai_id">
<input type="text" data-table="ganti_jk_d" data-field="x_pegawai_id" name="x_pegawai_id" id="x_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jk_d->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_d->pegawai_id->EditValue ?>"<?php echo $ganti_jk_d->pegawai_id->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_d->pegawai_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jk_d->libur->Visible) { // libur ?>
	<div id="r_libur" class="form-group">
		<label id="elh_ganti_jk_d_libur" for="x_libur" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jk_d->libur->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jk_d->libur->CellAttributes() ?>>
<span id="el_ganti_jk_d_libur">
<input type="text" data-table="ganti_jk_d" data-field="x_libur" name="x_libur" id="x_libur" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jk_d->libur->getPlaceHolder()) ?>" value="<?php echo $ganti_jk_d->libur->EditValue ?>"<?php echo $ganti_jk_d->libur->EditAttributes() ?>>
</span>
<?php echo $ganti_jk_d->libur->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$ganti_jk_d_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $ganti_jk_d_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fganti_jk_dadd.Init();
</script>
<?php
$ganti_jk_d_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ganti_jk_d_add->Page_Terminate();
?>
