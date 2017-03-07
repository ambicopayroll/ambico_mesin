<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "jam_kerjainfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$jam_kerja_delete = NULL; // Initialize page object first

class cjam_kerja_delete extends cjam_kerja {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'jam_kerja';

	// Page object name
	var $PageObjName = 'jam_kerja_delete';

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

		// Table object (jam_kerja)
		if (!isset($GLOBALS["jam_kerja"]) || get_class($GLOBALS["jam_kerja"]) == "cjam_kerja") {
			$GLOBALS["jam_kerja"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jam_kerja"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jam_kerja', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("jam_kerjalist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->jk_id->SetVisibility();
		$this->jk_name->SetVisibility();
		$this->jk_kode->SetVisibility();
		$this->use_set->SetVisibility();
		$this->jk_bcin->SetVisibility();
		$this->jk_cin->SetVisibility();
		$this->jk_ecin->SetVisibility();
		$this->jk_tol_late->SetVisibility();
		$this->jk_use_ist->SetVisibility();
		$this->jk_ist1->SetVisibility();
		$this->jk_ist2->SetVisibility();
		$this->jk_tol_early->SetVisibility();
		$this->jk_bcout->SetVisibility();
		$this->jk_cout->SetVisibility();
		$this->jk_ecout->SetVisibility();
		$this->use_eot->SetVisibility();
		$this->min_eot->SetVisibility();
		$this->max_eot->SetVisibility();
		$this->reduce_eot->SetVisibility();
		$this->jk_durasi->SetVisibility();
		$this->jk_countas->SetVisibility();
		$this->jk_min_countas->SetVisibility();
		$this->jk_ket->SetVisibility();

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
		global $EW_EXPORT, $jam_kerja;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($jam_kerja);
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
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("jam_kerjalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in jam_kerja class, jam_kerjainfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("jam_kerjalist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->jk_id->setDbValue($rs->fields('jk_id'));
		$this->jk_name->setDbValue($rs->fields('jk_name'));
		$this->jk_kode->setDbValue($rs->fields('jk_kode'));
		$this->use_set->setDbValue($rs->fields('use_set'));
		$this->jk_bcin->setDbValue($rs->fields('jk_bcin'));
		$this->jk_cin->setDbValue($rs->fields('jk_cin'));
		$this->jk_ecin->setDbValue($rs->fields('jk_ecin'));
		$this->jk_tol_late->setDbValue($rs->fields('jk_tol_late'));
		$this->jk_use_ist->setDbValue($rs->fields('jk_use_ist'));
		$this->jk_ist1->setDbValue($rs->fields('jk_ist1'));
		$this->jk_ist2->setDbValue($rs->fields('jk_ist2'));
		$this->jk_tol_early->setDbValue($rs->fields('jk_tol_early'));
		$this->jk_bcout->setDbValue($rs->fields('jk_bcout'));
		$this->jk_cout->setDbValue($rs->fields('jk_cout'));
		$this->jk_ecout->setDbValue($rs->fields('jk_ecout'));
		$this->use_eot->setDbValue($rs->fields('use_eot'));
		$this->min_eot->setDbValue($rs->fields('min_eot'));
		$this->max_eot->setDbValue($rs->fields('max_eot'));
		$this->reduce_eot->setDbValue($rs->fields('reduce_eot'));
		$this->jk_durasi->setDbValue($rs->fields('jk_durasi'));
		$this->jk_countas->setDbValue($rs->fields('jk_countas'));
		$this->jk_min_countas->setDbValue($rs->fields('jk_min_countas'));
		$this->jk_ket->setDbValue($rs->fields('jk_ket'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->jk_id->DbValue = $row['jk_id'];
		$this->jk_name->DbValue = $row['jk_name'];
		$this->jk_kode->DbValue = $row['jk_kode'];
		$this->use_set->DbValue = $row['use_set'];
		$this->jk_bcin->DbValue = $row['jk_bcin'];
		$this->jk_cin->DbValue = $row['jk_cin'];
		$this->jk_ecin->DbValue = $row['jk_ecin'];
		$this->jk_tol_late->DbValue = $row['jk_tol_late'];
		$this->jk_use_ist->DbValue = $row['jk_use_ist'];
		$this->jk_ist1->DbValue = $row['jk_ist1'];
		$this->jk_ist2->DbValue = $row['jk_ist2'];
		$this->jk_tol_early->DbValue = $row['jk_tol_early'];
		$this->jk_bcout->DbValue = $row['jk_bcout'];
		$this->jk_cout->DbValue = $row['jk_cout'];
		$this->jk_ecout->DbValue = $row['jk_ecout'];
		$this->use_eot->DbValue = $row['use_eot'];
		$this->min_eot->DbValue = $row['min_eot'];
		$this->max_eot->DbValue = $row['max_eot'];
		$this->reduce_eot->DbValue = $row['reduce_eot'];
		$this->jk_durasi->DbValue = $row['jk_durasi'];
		$this->jk_countas->DbValue = $row['jk_countas'];
		$this->jk_min_countas->DbValue = $row['jk_min_countas'];
		$this->jk_ket->DbValue = $row['jk_ket'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->jk_countas->FormValue == $this->jk_countas->CurrentValue && is_numeric(ew_StrToFloat($this->jk_countas->CurrentValue)))
			$this->jk_countas->CurrentValue = ew_StrToFloat($this->jk_countas->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// jk_id
		// jk_name
		// jk_kode
		// use_set
		// jk_bcin
		// jk_cin
		// jk_ecin
		// jk_tol_late
		// jk_use_ist
		// jk_ist1
		// jk_ist2
		// jk_tol_early
		// jk_bcout
		// jk_cout
		// jk_ecout
		// use_eot
		// min_eot
		// max_eot
		// reduce_eot
		// jk_durasi
		// jk_countas
		// jk_min_countas
		// jk_ket

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// jk_id
		$this->jk_id->ViewValue = $this->jk_id->CurrentValue;
		$this->jk_id->ViewCustomAttributes = "";

		// jk_name
		$this->jk_name->ViewValue = $this->jk_name->CurrentValue;
		$this->jk_name->ViewCustomAttributes = "";

		// jk_kode
		$this->jk_kode->ViewValue = $this->jk_kode->CurrentValue;
		$this->jk_kode->ViewCustomAttributes = "";

		// use_set
		$this->use_set->ViewValue = $this->use_set->CurrentValue;
		$this->use_set->ViewCustomAttributes = "";

		// jk_bcin
		$this->jk_bcin->ViewValue = $this->jk_bcin->CurrentValue;
		$this->jk_bcin->ViewCustomAttributes = "";

		// jk_cin
		$this->jk_cin->ViewValue = $this->jk_cin->CurrentValue;
		$this->jk_cin->ViewCustomAttributes = "";

		// jk_ecin
		$this->jk_ecin->ViewValue = $this->jk_ecin->CurrentValue;
		$this->jk_ecin->ViewCustomAttributes = "";

		// jk_tol_late
		$this->jk_tol_late->ViewValue = $this->jk_tol_late->CurrentValue;
		$this->jk_tol_late->ViewCustomAttributes = "";

		// jk_use_ist
		$this->jk_use_ist->ViewValue = $this->jk_use_ist->CurrentValue;
		$this->jk_use_ist->ViewCustomAttributes = "";

		// jk_ist1
		$this->jk_ist1->ViewValue = $this->jk_ist1->CurrentValue;
		$this->jk_ist1->ViewCustomAttributes = "";

		// jk_ist2
		$this->jk_ist2->ViewValue = $this->jk_ist2->CurrentValue;
		$this->jk_ist2->ViewCustomAttributes = "";

		// jk_tol_early
		$this->jk_tol_early->ViewValue = $this->jk_tol_early->CurrentValue;
		$this->jk_tol_early->ViewCustomAttributes = "";

		// jk_bcout
		$this->jk_bcout->ViewValue = $this->jk_bcout->CurrentValue;
		$this->jk_bcout->ViewCustomAttributes = "";

		// jk_cout
		$this->jk_cout->ViewValue = $this->jk_cout->CurrentValue;
		$this->jk_cout->ViewCustomAttributes = "";

		// jk_ecout
		$this->jk_ecout->ViewValue = $this->jk_ecout->CurrentValue;
		$this->jk_ecout->ViewCustomAttributes = "";

		// use_eot
		$this->use_eot->ViewValue = $this->use_eot->CurrentValue;
		$this->use_eot->ViewCustomAttributes = "";

		// min_eot
		$this->min_eot->ViewValue = $this->min_eot->CurrentValue;
		$this->min_eot->ViewCustomAttributes = "";

		// max_eot
		$this->max_eot->ViewValue = $this->max_eot->CurrentValue;
		$this->max_eot->ViewCustomAttributes = "";

		// reduce_eot
		$this->reduce_eot->ViewValue = $this->reduce_eot->CurrentValue;
		$this->reduce_eot->ViewCustomAttributes = "";

		// jk_durasi
		$this->jk_durasi->ViewValue = $this->jk_durasi->CurrentValue;
		$this->jk_durasi->ViewCustomAttributes = "";

		// jk_countas
		$this->jk_countas->ViewValue = $this->jk_countas->CurrentValue;
		$this->jk_countas->ViewCustomAttributes = "";

		// jk_min_countas
		$this->jk_min_countas->ViewValue = $this->jk_min_countas->CurrentValue;
		$this->jk_min_countas->ViewCustomAttributes = "";

		// jk_ket
		$this->jk_ket->ViewValue = $this->jk_ket->CurrentValue;
		$this->jk_ket->ViewCustomAttributes = "";

			// jk_id
			$this->jk_id->LinkCustomAttributes = "";
			$this->jk_id->HrefValue = "";
			$this->jk_id->TooltipValue = "";

			// jk_name
			$this->jk_name->LinkCustomAttributes = "";
			$this->jk_name->HrefValue = "";
			$this->jk_name->TooltipValue = "";

			// jk_kode
			$this->jk_kode->LinkCustomAttributes = "";
			$this->jk_kode->HrefValue = "";
			$this->jk_kode->TooltipValue = "";

			// use_set
			$this->use_set->LinkCustomAttributes = "";
			$this->use_set->HrefValue = "";
			$this->use_set->TooltipValue = "";

			// jk_bcin
			$this->jk_bcin->LinkCustomAttributes = "";
			$this->jk_bcin->HrefValue = "";
			$this->jk_bcin->TooltipValue = "";

			// jk_cin
			$this->jk_cin->LinkCustomAttributes = "";
			$this->jk_cin->HrefValue = "";
			$this->jk_cin->TooltipValue = "";

			// jk_ecin
			$this->jk_ecin->LinkCustomAttributes = "";
			$this->jk_ecin->HrefValue = "";
			$this->jk_ecin->TooltipValue = "";

			// jk_tol_late
			$this->jk_tol_late->LinkCustomAttributes = "";
			$this->jk_tol_late->HrefValue = "";
			$this->jk_tol_late->TooltipValue = "";

			// jk_use_ist
			$this->jk_use_ist->LinkCustomAttributes = "";
			$this->jk_use_ist->HrefValue = "";
			$this->jk_use_ist->TooltipValue = "";

			// jk_ist1
			$this->jk_ist1->LinkCustomAttributes = "";
			$this->jk_ist1->HrefValue = "";
			$this->jk_ist1->TooltipValue = "";

			// jk_ist2
			$this->jk_ist2->LinkCustomAttributes = "";
			$this->jk_ist2->HrefValue = "";
			$this->jk_ist2->TooltipValue = "";

			// jk_tol_early
			$this->jk_tol_early->LinkCustomAttributes = "";
			$this->jk_tol_early->HrefValue = "";
			$this->jk_tol_early->TooltipValue = "";

			// jk_bcout
			$this->jk_bcout->LinkCustomAttributes = "";
			$this->jk_bcout->HrefValue = "";
			$this->jk_bcout->TooltipValue = "";

			// jk_cout
			$this->jk_cout->LinkCustomAttributes = "";
			$this->jk_cout->HrefValue = "";
			$this->jk_cout->TooltipValue = "";

			// jk_ecout
			$this->jk_ecout->LinkCustomAttributes = "";
			$this->jk_ecout->HrefValue = "";
			$this->jk_ecout->TooltipValue = "";

			// use_eot
			$this->use_eot->LinkCustomAttributes = "";
			$this->use_eot->HrefValue = "";
			$this->use_eot->TooltipValue = "";

			// min_eot
			$this->min_eot->LinkCustomAttributes = "";
			$this->min_eot->HrefValue = "";
			$this->min_eot->TooltipValue = "";

			// max_eot
			$this->max_eot->LinkCustomAttributes = "";
			$this->max_eot->HrefValue = "";
			$this->max_eot->TooltipValue = "";

			// reduce_eot
			$this->reduce_eot->LinkCustomAttributes = "";
			$this->reduce_eot->HrefValue = "";
			$this->reduce_eot->TooltipValue = "";

			// jk_durasi
			$this->jk_durasi->LinkCustomAttributes = "";
			$this->jk_durasi->HrefValue = "";
			$this->jk_durasi->TooltipValue = "";

			// jk_countas
			$this->jk_countas->LinkCustomAttributes = "";
			$this->jk_countas->HrefValue = "";
			$this->jk_countas->TooltipValue = "";

			// jk_min_countas
			$this->jk_min_countas->LinkCustomAttributes = "";
			$this->jk_min_countas->HrefValue = "";
			$this->jk_min_countas->TooltipValue = "";

			// jk_ket
			$this->jk_ket->LinkCustomAttributes = "";
			$this->jk_ket->HrefValue = "";
			$this->jk_ket->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['jk_id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("jam_kerjalist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($jam_kerja_delete)) $jam_kerja_delete = new cjam_kerja_delete();

// Page init
$jam_kerja_delete->Page_Init();

// Page main
$jam_kerja_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jam_kerja_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fjam_kerjadelete = new ew_Form("fjam_kerjadelete", "delete");

// Form_CustomValidate event
fjam_kerjadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjam_kerjadelete.ValidateRequired = true;
<?php } else { ?>
fjam_kerjadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $jam_kerja_delete->ShowPageHeader(); ?>
<?php
$jam_kerja_delete->ShowMessage();
?>
<form name="fjam_kerjadelete" id="fjam_kerjadelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jam_kerja_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jam_kerja_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jam_kerja">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($jam_kerja_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $jam_kerja->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($jam_kerja->jk_id->Visible) { // jk_id ?>
		<th><span id="elh_jam_kerja_jk_id" class="jam_kerja_jk_id"><?php echo $jam_kerja->jk_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_name->Visible) { // jk_name ?>
		<th><span id="elh_jam_kerja_jk_name" class="jam_kerja_jk_name"><?php echo $jam_kerja->jk_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_kode->Visible) { // jk_kode ?>
		<th><span id="elh_jam_kerja_jk_kode" class="jam_kerja_jk_kode"><?php echo $jam_kerja->jk_kode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->use_set->Visible) { // use_set ?>
		<th><span id="elh_jam_kerja_use_set" class="jam_kerja_use_set"><?php echo $jam_kerja->use_set->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_bcin->Visible) { // jk_bcin ?>
		<th><span id="elh_jam_kerja_jk_bcin" class="jam_kerja_jk_bcin"><?php echo $jam_kerja->jk_bcin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_cin->Visible) { // jk_cin ?>
		<th><span id="elh_jam_kerja_jk_cin" class="jam_kerja_jk_cin"><?php echo $jam_kerja->jk_cin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_ecin->Visible) { // jk_ecin ?>
		<th><span id="elh_jam_kerja_jk_ecin" class="jam_kerja_jk_ecin"><?php echo $jam_kerja->jk_ecin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_tol_late->Visible) { // jk_tol_late ?>
		<th><span id="elh_jam_kerja_jk_tol_late" class="jam_kerja_jk_tol_late"><?php echo $jam_kerja->jk_tol_late->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_use_ist->Visible) { // jk_use_ist ?>
		<th><span id="elh_jam_kerja_jk_use_ist" class="jam_kerja_jk_use_ist"><?php echo $jam_kerja->jk_use_ist->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_ist1->Visible) { // jk_ist1 ?>
		<th><span id="elh_jam_kerja_jk_ist1" class="jam_kerja_jk_ist1"><?php echo $jam_kerja->jk_ist1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_ist2->Visible) { // jk_ist2 ?>
		<th><span id="elh_jam_kerja_jk_ist2" class="jam_kerja_jk_ist2"><?php echo $jam_kerja->jk_ist2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_tol_early->Visible) { // jk_tol_early ?>
		<th><span id="elh_jam_kerja_jk_tol_early" class="jam_kerja_jk_tol_early"><?php echo $jam_kerja->jk_tol_early->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_bcout->Visible) { // jk_bcout ?>
		<th><span id="elh_jam_kerja_jk_bcout" class="jam_kerja_jk_bcout"><?php echo $jam_kerja->jk_bcout->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_cout->Visible) { // jk_cout ?>
		<th><span id="elh_jam_kerja_jk_cout" class="jam_kerja_jk_cout"><?php echo $jam_kerja->jk_cout->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_ecout->Visible) { // jk_ecout ?>
		<th><span id="elh_jam_kerja_jk_ecout" class="jam_kerja_jk_ecout"><?php echo $jam_kerja->jk_ecout->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->use_eot->Visible) { // use_eot ?>
		<th><span id="elh_jam_kerja_use_eot" class="jam_kerja_use_eot"><?php echo $jam_kerja->use_eot->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->min_eot->Visible) { // min_eot ?>
		<th><span id="elh_jam_kerja_min_eot" class="jam_kerja_min_eot"><?php echo $jam_kerja->min_eot->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->max_eot->Visible) { // max_eot ?>
		<th><span id="elh_jam_kerja_max_eot" class="jam_kerja_max_eot"><?php echo $jam_kerja->max_eot->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->reduce_eot->Visible) { // reduce_eot ?>
		<th><span id="elh_jam_kerja_reduce_eot" class="jam_kerja_reduce_eot"><?php echo $jam_kerja->reduce_eot->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_durasi->Visible) { // jk_durasi ?>
		<th><span id="elh_jam_kerja_jk_durasi" class="jam_kerja_jk_durasi"><?php echo $jam_kerja->jk_durasi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_countas->Visible) { // jk_countas ?>
		<th><span id="elh_jam_kerja_jk_countas" class="jam_kerja_jk_countas"><?php echo $jam_kerja->jk_countas->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_min_countas->Visible) { // jk_min_countas ?>
		<th><span id="elh_jam_kerja_jk_min_countas" class="jam_kerja_jk_min_countas"><?php echo $jam_kerja->jk_min_countas->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jam_kerja->jk_ket->Visible) { // jk_ket ?>
		<th><span id="elh_jam_kerja_jk_ket" class="jam_kerja_jk_ket"><?php echo $jam_kerja->jk_ket->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$jam_kerja_delete->RecCnt = 0;
$i = 0;
while (!$jam_kerja_delete->Recordset->EOF) {
	$jam_kerja_delete->RecCnt++;
	$jam_kerja_delete->RowCnt++;

	// Set row properties
	$jam_kerja->ResetAttrs();
	$jam_kerja->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$jam_kerja_delete->LoadRowValues($jam_kerja_delete->Recordset);

	// Render row
	$jam_kerja_delete->RenderRow();
?>
	<tr<?php echo $jam_kerja->RowAttributes() ?>>
<?php if ($jam_kerja->jk_id->Visible) { // jk_id ?>
		<td<?php echo $jam_kerja->jk_id->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_id" class="jam_kerja_jk_id">
<span<?php echo $jam_kerja->jk_id->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_name->Visible) { // jk_name ?>
		<td<?php echo $jam_kerja->jk_name->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_name" class="jam_kerja_jk_name">
<span<?php echo $jam_kerja->jk_name->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_kode->Visible) { // jk_kode ?>
		<td<?php echo $jam_kerja->jk_kode->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_kode" class="jam_kerja_jk_kode">
<span<?php echo $jam_kerja->jk_kode->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_kode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->use_set->Visible) { // use_set ?>
		<td<?php echo $jam_kerja->use_set->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_use_set" class="jam_kerja_use_set">
<span<?php echo $jam_kerja->use_set->ViewAttributes() ?>>
<?php echo $jam_kerja->use_set->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_bcin->Visible) { // jk_bcin ?>
		<td<?php echo $jam_kerja->jk_bcin->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_bcin" class="jam_kerja_jk_bcin">
<span<?php echo $jam_kerja->jk_bcin->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_bcin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_cin->Visible) { // jk_cin ?>
		<td<?php echo $jam_kerja->jk_cin->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_cin" class="jam_kerja_jk_cin">
<span<?php echo $jam_kerja->jk_cin->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_cin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_ecin->Visible) { // jk_ecin ?>
		<td<?php echo $jam_kerja->jk_ecin->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_ecin" class="jam_kerja_jk_ecin">
<span<?php echo $jam_kerja->jk_ecin->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ecin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_tol_late->Visible) { // jk_tol_late ?>
		<td<?php echo $jam_kerja->jk_tol_late->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_tol_late" class="jam_kerja_jk_tol_late">
<span<?php echo $jam_kerja->jk_tol_late->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_tol_late->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_use_ist->Visible) { // jk_use_ist ?>
		<td<?php echo $jam_kerja->jk_use_ist->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_use_ist" class="jam_kerja_jk_use_ist">
<span<?php echo $jam_kerja->jk_use_ist->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_use_ist->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_ist1->Visible) { // jk_ist1 ?>
		<td<?php echo $jam_kerja->jk_ist1->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_ist1" class="jam_kerja_jk_ist1">
<span<?php echo $jam_kerja->jk_ist1->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ist1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_ist2->Visible) { // jk_ist2 ?>
		<td<?php echo $jam_kerja->jk_ist2->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_ist2" class="jam_kerja_jk_ist2">
<span<?php echo $jam_kerja->jk_ist2->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ist2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_tol_early->Visible) { // jk_tol_early ?>
		<td<?php echo $jam_kerja->jk_tol_early->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_tol_early" class="jam_kerja_jk_tol_early">
<span<?php echo $jam_kerja->jk_tol_early->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_tol_early->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_bcout->Visible) { // jk_bcout ?>
		<td<?php echo $jam_kerja->jk_bcout->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_bcout" class="jam_kerja_jk_bcout">
<span<?php echo $jam_kerja->jk_bcout->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_bcout->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_cout->Visible) { // jk_cout ?>
		<td<?php echo $jam_kerja->jk_cout->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_cout" class="jam_kerja_jk_cout">
<span<?php echo $jam_kerja->jk_cout->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_cout->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_ecout->Visible) { // jk_ecout ?>
		<td<?php echo $jam_kerja->jk_ecout->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_ecout" class="jam_kerja_jk_ecout">
<span<?php echo $jam_kerja->jk_ecout->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ecout->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->use_eot->Visible) { // use_eot ?>
		<td<?php echo $jam_kerja->use_eot->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_use_eot" class="jam_kerja_use_eot">
<span<?php echo $jam_kerja->use_eot->ViewAttributes() ?>>
<?php echo $jam_kerja->use_eot->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->min_eot->Visible) { // min_eot ?>
		<td<?php echo $jam_kerja->min_eot->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_min_eot" class="jam_kerja_min_eot">
<span<?php echo $jam_kerja->min_eot->ViewAttributes() ?>>
<?php echo $jam_kerja->min_eot->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->max_eot->Visible) { // max_eot ?>
		<td<?php echo $jam_kerja->max_eot->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_max_eot" class="jam_kerja_max_eot">
<span<?php echo $jam_kerja->max_eot->ViewAttributes() ?>>
<?php echo $jam_kerja->max_eot->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->reduce_eot->Visible) { // reduce_eot ?>
		<td<?php echo $jam_kerja->reduce_eot->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_reduce_eot" class="jam_kerja_reduce_eot">
<span<?php echo $jam_kerja->reduce_eot->ViewAttributes() ?>>
<?php echo $jam_kerja->reduce_eot->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_durasi->Visible) { // jk_durasi ?>
		<td<?php echo $jam_kerja->jk_durasi->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_durasi" class="jam_kerja_jk_durasi">
<span<?php echo $jam_kerja->jk_durasi->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_durasi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_countas->Visible) { // jk_countas ?>
		<td<?php echo $jam_kerja->jk_countas->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_countas" class="jam_kerja_jk_countas">
<span<?php echo $jam_kerja->jk_countas->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_countas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_min_countas->Visible) { // jk_min_countas ?>
		<td<?php echo $jam_kerja->jk_min_countas->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_min_countas" class="jam_kerja_jk_min_countas">
<span<?php echo $jam_kerja->jk_min_countas->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_min_countas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jam_kerja->jk_ket->Visible) { // jk_ket ?>
		<td<?php echo $jam_kerja->jk_ket->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_delete->RowCnt ?>_jam_kerja_jk_ket" class="jam_kerja_jk_ket">
<span<?php echo $jam_kerja->jk_ket->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ket->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$jam_kerja_delete->Recordset->MoveNext();
}
$jam_kerja_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $jam_kerja_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fjam_kerjadelete.Init();
</script>
<?php
$jam_kerja_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jam_kerja_delete->Page_Terminate();
?>
