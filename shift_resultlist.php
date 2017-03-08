<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "shift_resultinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$shift_result_list = NULL; // Initialize page object first

class cshift_result_list extends cshift_result {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'shift_result';

	// Page object name
	var $PageObjName = 'shift_result_list';

	// Grid form hidden field names
	var $FormName = 'fshift_resultlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (shift_result)
		if (!isset($GLOBALS["shift_result"]) || get_class($GLOBALS["shift_result"]) == "cshift_result") {
			$GLOBALS["shift_result"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["shift_result"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "shift_resultadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "shift_resultdelete.php";
		$this->MultiUpdateUrl = "shift_resultupdate.php";

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'shift_result', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_user();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fshift_resultlistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->pegawai_id->SetVisibility();
		$this->tgl_shift->SetVisibility();
		$this->khusus_lembur->SetVisibility();
		$this->khusus_extra->SetVisibility();
		$this->temp_id_auto->SetVisibility();
		$this->jdw_kerja_m_id->SetVisibility();
		$this->jk_id->SetVisibility();
		$this->jns_dok->SetVisibility();
		$this->izin_jenis_id->SetVisibility();
		$this->cuti_n_id->SetVisibility();
		$this->libur_umum->SetVisibility();
		$this->libur_rutin->SetVisibility();
		$this->jk_ot->SetVisibility();
		$this->scan_in->SetVisibility();
		$this->att_id_in->SetVisibility();
		$this->late_permission->SetVisibility();
		$this->late_minute->SetVisibility();
		$this->late->SetVisibility();
		$this->break_out->SetVisibility();
		$this->att_id_break1->SetVisibility();
		$this->break_in->SetVisibility();
		$this->att_id_break2->SetVisibility();
		$this->break_minute->SetVisibility();
		$this->break->SetVisibility();
		$this->break_ot_minute->SetVisibility();
		$this->break_ot->SetVisibility();
		$this->early_permission->SetVisibility();
		$this->early_minute->SetVisibility();
		$this->early->SetVisibility();
		$this->scan_out->SetVisibility();
		$this->att_id_out->SetVisibility();
		$this->durasi_minute->SetVisibility();
		$this->durasi->SetVisibility();
		$this->durasi_eot_minute->SetVisibility();
		$this->jk_count_as->SetVisibility();
		$this->status_jk->SetVisibility();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
		global $EW_EXPORT, $shift_result;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($shift_result);
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 5) {
			$this->pegawai_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->pegawai_id->FormValue))
				return FALSE;
			$this->tgl_shift->setFormValue($arrKeyFlds[1]);
			$this->khusus_lembur->setFormValue($arrKeyFlds[2]);
			if (!is_numeric($this->khusus_lembur->FormValue))
				return FALSE;
			$this->khusus_extra->setFormValue($arrKeyFlds[3]);
			if (!is_numeric($this->khusus_extra->FormValue))
				return FALSE;
			$this->temp_id_auto->setFormValue($arrKeyFlds[4]);
			if (!is_numeric($this->temp_id_auto->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fshift_resultlistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->pegawai_id->AdvancedSearch->ToJSON(), ","); // Field pegawai_id
		$sFilterList = ew_Concat($sFilterList, $this->tgl_shift->AdvancedSearch->ToJSON(), ","); // Field tgl_shift
		$sFilterList = ew_Concat($sFilterList, $this->khusus_lembur->AdvancedSearch->ToJSON(), ","); // Field khusus_lembur
		$sFilterList = ew_Concat($sFilterList, $this->khusus_extra->AdvancedSearch->ToJSON(), ","); // Field khusus_extra
		$sFilterList = ew_Concat($sFilterList, $this->temp_id_auto->AdvancedSearch->ToJSON(), ","); // Field temp_id_auto
		$sFilterList = ew_Concat($sFilterList, $this->jdw_kerja_m_id->AdvancedSearch->ToJSON(), ","); // Field jdw_kerja_m_id
		$sFilterList = ew_Concat($sFilterList, $this->jk_id->AdvancedSearch->ToJSON(), ","); // Field jk_id
		$sFilterList = ew_Concat($sFilterList, $this->jns_dok->AdvancedSearch->ToJSON(), ","); // Field jns_dok
		$sFilterList = ew_Concat($sFilterList, $this->izin_jenis_id->AdvancedSearch->ToJSON(), ","); // Field izin_jenis_id
		$sFilterList = ew_Concat($sFilterList, $this->cuti_n_id->AdvancedSearch->ToJSON(), ","); // Field cuti_n_id
		$sFilterList = ew_Concat($sFilterList, $this->libur_umum->AdvancedSearch->ToJSON(), ","); // Field libur_umum
		$sFilterList = ew_Concat($sFilterList, $this->libur_rutin->AdvancedSearch->ToJSON(), ","); // Field libur_rutin
		$sFilterList = ew_Concat($sFilterList, $this->jk_ot->AdvancedSearch->ToJSON(), ","); // Field jk_ot
		$sFilterList = ew_Concat($sFilterList, $this->scan_in->AdvancedSearch->ToJSON(), ","); // Field scan_in
		$sFilterList = ew_Concat($sFilterList, $this->att_id_in->AdvancedSearch->ToJSON(), ","); // Field att_id_in
		$sFilterList = ew_Concat($sFilterList, $this->late_permission->AdvancedSearch->ToJSON(), ","); // Field late_permission
		$sFilterList = ew_Concat($sFilterList, $this->late_minute->AdvancedSearch->ToJSON(), ","); // Field late_minute
		$sFilterList = ew_Concat($sFilterList, $this->late->AdvancedSearch->ToJSON(), ","); // Field late
		$sFilterList = ew_Concat($sFilterList, $this->break_out->AdvancedSearch->ToJSON(), ","); // Field break_out
		$sFilterList = ew_Concat($sFilterList, $this->att_id_break1->AdvancedSearch->ToJSON(), ","); // Field att_id_break1
		$sFilterList = ew_Concat($sFilterList, $this->break_in->AdvancedSearch->ToJSON(), ","); // Field break_in
		$sFilterList = ew_Concat($sFilterList, $this->att_id_break2->AdvancedSearch->ToJSON(), ","); // Field att_id_break2
		$sFilterList = ew_Concat($sFilterList, $this->break_minute->AdvancedSearch->ToJSON(), ","); // Field break_minute
		$sFilterList = ew_Concat($sFilterList, $this->break->AdvancedSearch->ToJSON(), ","); // Field break
		$sFilterList = ew_Concat($sFilterList, $this->break_ot_minute->AdvancedSearch->ToJSON(), ","); // Field break_ot_minute
		$sFilterList = ew_Concat($sFilterList, $this->break_ot->AdvancedSearch->ToJSON(), ","); // Field break_ot
		$sFilterList = ew_Concat($sFilterList, $this->early_permission->AdvancedSearch->ToJSON(), ","); // Field early_permission
		$sFilterList = ew_Concat($sFilterList, $this->early_minute->AdvancedSearch->ToJSON(), ","); // Field early_minute
		$sFilterList = ew_Concat($sFilterList, $this->early->AdvancedSearch->ToJSON(), ","); // Field early
		$sFilterList = ew_Concat($sFilterList, $this->scan_out->AdvancedSearch->ToJSON(), ","); // Field scan_out
		$sFilterList = ew_Concat($sFilterList, $this->att_id_out->AdvancedSearch->ToJSON(), ","); // Field att_id_out
		$sFilterList = ew_Concat($sFilterList, $this->durasi_minute->AdvancedSearch->ToJSON(), ","); // Field durasi_minute
		$sFilterList = ew_Concat($sFilterList, $this->durasi->AdvancedSearch->ToJSON(), ","); // Field durasi
		$sFilterList = ew_Concat($sFilterList, $this->durasi_eot_minute->AdvancedSearch->ToJSON(), ","); // Field durasi_eot_minute
		$sFilterList = ew_Concat($sFilterList, $this->jk_count_as->AdvancedSearch->ToJSON(), ","); // Field jk_count_as
		$sFilterList = ew_Concat($sFilterList, $this->status_jk->AdvancedSearch->ToJSON(), ","); // Field status_jk
		$sFilterList = ew_Concat($sFilterList, $this->keterangan->AdvancedSearch->ToJSON(), ","); // Field keterangan
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "fshift_resultlistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field pegawai_id
		$this->pegawai_id->AdvancedSearch->SearchValue = @$filter["x_pegawai_id"];
		$this->pegawai_id->AdvancedSearch->SearchOperator = @$filter["z_pegawai_id"];
		$this->pegawai_id->AdvancedSearch->SearchCondition = @$filter["v_pegawai_id"];
		$this->pegawai_id->AdvancedSearch->SearchValue2 = @$filter["y_pegawai_id"];
		$this->pegawai_id->AdvancedSearch->SearchOperator2 = @$filter["w_pegawai_id"];
		$this->pegawai_id->AdvancedSearch->Save();

		// Field tgl_shift
		$this->tgl_shift->AdvancedSearch->SearchValue = @$filter["x_tgl_shift"];
		$this->tgl_shift->AdvancedSearch->SearchOperator = @$filter["z_tgl_shift"];
		$this->tgl_shift->AdvancedSearch->SearchCondition = @$filter["v_tgl_shift"];
		$this->tgl_shift->AdvancedSearch->SearchValue2 = @$filter["y_tgl_shift"];
		$this->tgl_shift->AdvancedSearch->SearchOperator2 = @$filter["w_tgl_shift"];
		$this->tgl_shift->AdvancedSearch->Save();

		// Field khusus_lembur
		$this->khusus_lembur->AdvancedSearch->SearchValue = @$filter["x_khusus_lembur"];
		$this->khusus_lembur->AdvancedSearch->SearchOperator = @$filter["z_khusus_lembur"];
		$this->khusus_lembur->AdvancedSearch->SearchCondition = @$filter["v_khusus_lembur"];
		$this->khusus_lembur->AdvancedSearch->SearchValue2 = @$filter["y_khusus_lembur"];
		$this->khusus_lembur->AdvancedSearch->SearchOperator2 = @$filter["w_khusus_lembur"];
		$this->khusus_lembur->AdvancedSearch->Save();

		// Field khusus_extra
		$this->khusus_extra->AdvancedSearch->SearchValue = @$filter["x_khusus_extra"];
		$this->khusus_extra->AdvancedSearch->SearchOperator = @$filter["z_khusus_extra"];
		$this->khusus_extra->AdvancedSearch->SearchCondition = @$filter["v_khusus_extra"];
		$this->khusus_extra->AdvancedSearch->SearchValue2 = @$filter["y_khusus_extra"];
		$this->khusus_extra->AdvancedSearch->SearchOperator2 = @$filter["w_khusus_extra"];
		$this->khusus_extra->AdvancedSearch->Save();

		// Field temp_id_auto
		$this->temp_id_auto->AdvancedSearch->SearchValue = @$filter["x_temp_id_auto"];
		$this->temp_id_auto->AdvancedSearch->SearchOperator = @$filter["z_temp_id_auto"];
		$this->temp_id_auto->AdvancedSearch->SearchCondition = @$filter["v_temp_id_auto"];
		$this->temp_id_auto->AdvancedSearch->SearchValue2 = @$filter["y_temp_id_auto"];
		$this->temp_id_auto->AdvancedSearch->SearchOperator2 = @$filter["w_temp_id_auto"];
		$this->temp_id_auto->AdvancedSearch->Save();

		// Field jdw_kerja_m_id
		$this->jdw_kerja_m_id->AdvancedSearch->SearchValue = @$filter["x_jdw_kerja_m_id"];
		$this->jdw_kerja_m_id->AdvancedSearch->SearchOperator = @$filter["z_jdw_kerja_m_id"];
		$this->jdw_kerja_m_id->AdvancedSearch->SearchCondition = @$filter["v_jdw_kerja_m_id"];
		$this->jdw_kerja_m_id->AdvancedSearch->SearchValue2 = @$filter["y_jdw_kerja_m_id"];
		$this->jdw_kerja_m_id->AdvancedSearch->SearchOperator2 = @$filter["w_jdw_kerja_m_id"];
		$this->jdw_kerja_m_id->AdvancedSearch->Save();

		// Field jk_id
		$this->jk_id->AdvancedSearch->SearchValue = @$filter["x_jk_id"];
		$this->jk_id->AdvancedSearch->SearchOperator = @$filter["z_jk_id"];
		$this->jk_id->AdvancedSearch->SearchCondition = @$filter["v_jk_id"];
		$this->jk_id->AdvancedSearch->SearchValue2 = @$filter["y_jk_id"];
		$this->jk_id->AdvancedSearch->SearchOperator2 = @$filter["w_jk_id"];
		$this->jk_id->AdvancedSearch->Save();

		// Field jns_dok
		$this->jns_dok->AdvancedSearch->SearchValue = @$filter["x_jns_dok"];
		$this->jns_dok->AdvancedSearch->SearchOperator = @$filter["z_jns_dok"];
		$this->jns_dok->AdvancedSearch->SearchCondition = @$filter["v_jns_dok"];
		$this->jns_dok->AdvancedSearch->SearchValue2 = @$filter["y_jns_dok"];
		$this->jns_dok->AdvancedSearch->SearchOperator2 = @$filter["w_jns_dok"];
		$this->jns_dok->AdvancedSearch->Save();

		// Field izin_jenis_id
		$this->izin_jenis_id->AdvancedSearch->SearchValue = @$filter["x_izin_jenis_id"];
		$this->izin_jenis_id->AdvancedSearch->SearchOperator = @$filter["z_izin_jenis_id"];
		$this->izin_jenis_id->AdvancedSearch->SearchCondition = @$filter["v_izin_jenis_id"];
		$this->izin_jenis_id->AdvancedSearch->SearchValue2 = @$filter["y_izin_jenis_id"];
		$this->izin_jenis_id->AdvancedSearch->SearchOperator2 = @$filter["w_izin_jenis_id"];
		$this->izin_jenis_id->AdvancedSearch->Save();

		// Field cuti_n_id
		$this->cuti_n_id->AdvancedSearch->SearchValue = @$filter["x_cuti_n_id"];
		$this->cuti_n_id->AdvancedSearch->SearchOperator = @$filter["z_cuti_n_id"];
		$this->cuti_n_id->AdvancedSearch->SearchCondition = @$filter["v_cuti_n_id"];
		$this->cuti_n_id->AdvancedSearch->SearchValue2 = @$filter["y_cuti_n_id"];
		$this->cuti_n_id->AdvancedSearch->SearchOperator2 = @$filter["w_cuti_n_id"];
		$this->cuti_n_id->AdvancedSearch->Save();

		// Field libur_umum
		$this->libur_umum->AdvancedSearch->SearchValue = @$filter["x_libur_umum"];
		$this->libur_umum->AdvancedSearch->SearchOperator = @$filter["z_libur_umum"];
		$this->libur_umum->AdvancedSearch->SearchCondition = @$filter["v_libur_umum"];
		$this->libur_umum->AdvancedSearch->SearchValue2 = @$filter["y_libur_umum"];
		$this->libur_umum->AdvancedSearch->SearchOperator2 = @$filter["w_libur_umum"];
		$this->libur_umum->AdvancedSearch->Save();

		// Field libur_rutin
		$this->libur_rutin->AdvancedSearch->SearchValue = @$filter["x_libur_rutin"];
		$this->libur_rutin->AdvancedSearch->SearchOperator = @$filter["z_libur_rutin"];
		$this->libur_rutin->AdvancedSearch->SearchCondition = @$filter["v_libur_rutin"];
		$this->libur_rutin->AdvancedSearch->SearchValue2 = @$filter["y_libur_rutin"];
		$this->libur_rutin->AdvancedSearch->SearchOperator2 = @$filter["w_libur_rutin"];
		$this->libur_rutin->AdvancedSearch->Save();

		// Field jk_ot
		$this->jk_ot->AdvancedSearch->SearchValue = @$filter["x_jk_ot"];
		$this->jk_ot->AdvancedSearch->SearchOperator = @$filter["z_jk_ot"];
		$this->jk_ot->AdvancedSearch->SearchCondition = @$filter["v_jk_ot"];
		$this->jk_ot->AdvancedSearch->SearchValue2 = @$filter["y_jk_ot"];
		$this->jk_ot->AdvancedSearch->SearchOperator2 = @$filter["w_jk_ot"];
		$this->jk_ot->AdvancedSearch->Save();

		// Field scan_in
		$this->scan_in->AdvancedSearch->SearchValue = @$filter["x_scan_in"];
		$this->scan_in->AdvancedSearch->SearchOperator = @$filter["z_scan_in"];
		$this->scan_in->AdvancedSearch->SearchCondition = @$filter["v_scan_in"];
		$this->scan_in->AdvancedSearch->SearchValue2 = @$filter["y_scan_in"];
		$this->scan_in->AdvancedSearch->SearchOperator2 = @$filter["w_scan_in"];
		$this->scan_in->AdvancedSearch->Save();

		// Field att_id_in
		$this->att_id_in->AdvancedSearch->SearchValue = @$filter["x_att_id_in"];
		$this->att_id_in->AdvancedSearch->SearchOperator = @$filter["z_att_id_in"];
		$this->att_id_in->AdvancedSearch->SearchCondition = @$filter["v_att_id_in"];
		$this->att_id_in->AdvancedSearch->SearchValue2 = @$filter["y_att_id_in"];
		$this->att_id_in->AdvancedSearch->SearchOperator2 = @$filter["w_att_id_in"];
		$this->att_id_in->AdvancedSearch->Save();

		// Field late_permission
		$this->late_permission->AdvancedSearch->SearchValue = @$filter["x_late_permission"];
		$this->late_permission->AdvancedSearch->SearchOperator = @$filter["z_late_permission"];
		$this->late_permission->AdvancedSearch->SearchCondition = @$filter["v_late_permission"];
		$this->late_permission->AdvancedSearch->SearchValue2 = @$filter["y_late_permission"];
		$this->late_permission->AdvancedSearch->SearchOperator2 = @$filter["w_late_permission"];
		$this->late_permission->AdvancedSearch->Save();

		// Field late_minute
		$this->late_minute->AdvancedSearch->SearchValue = @$filter["x_late_minute"];
		$this->late_minute->AdvancedSearch->SearchOperator = @$filter["z_late_minute"];
		$this->late_minute->AdvancedSearch->SearchCondition = @$filter["v_late_minute"];
		$this->late_minute->AdvancedSearch->SearchValue2 = @$filter["y_late_minute"];
		$this->late_minute->AdvancedSearch->SearchOperator2 = @$filter["w_late_minute"];
		$this->late_minute->AdvancedSearch->Save();

		// Field late
		$this->late->AdvancedSearch->SearchValue = @$filter["x_late"];
		$this->late->AdvancedSearch->SearchOperator = @$filter["z_late"];
		$this->late->AdvancedSearch->SearchCondition = @$filter["v_late"];
		$this->late->AdvancedSearch->SearchValue2 = @$filter["y_late"];
		$this->late->AdvancedSearch->SearchOperator2 = @$filter["w_late"];
		$this->late->AdvancedSearch->Save();

		// Field break_out
		$this->break_out->AdvancedSearch->SearchValue = @$filter["x_break_out"];
		$this->break_out->AdvancedSearch->SearchOperator = @$filter["z_break_out"];
		$this->break_out->AdvancedSearch->SearchCondition = @$filter["v_break_out"];
		$this->break_out->AdvancedSearch->SearchValue2 = @$filter["y_break_out"];
		$this->break_out->AdvancedSearch->SearchOperator2 = @$filter["w_break_out"];
		$this->break_out->AdvancedSearch->Save();

		// Field att_id_break1
		$this->att_id_break1->AdvancedSearch->SearchValue = @$filter["x_att_id_break1"];
		$this->att_id_break1->AdvancedSearch->SearchOperator = @$filter["z_att_id_break1"];
		$this->att_id_break1->AdvancedSearch->SearchCondition = @$filter["v_att_id_break1"];
		$this->att_id_break1->AdvancedSearch->SearchValue2 = @$filter["y_att_id_break1"];
		$this->att_id_break1->AdvancedSearch->SearchOperator2 = @$filter["w_att_id_break1"];
		$this->att_id_break1->AdvancedSearch->Save();

		// Field break_in
		$this->break_in->AdvancedSearch->SearchValue = @$filter["x_break_in"];
		$this->break_in->AdvancedSearch->SearchOperator = @$filter["z_break_in"];
		$this->break_in->AdvancedSearch->SearchCondition = @$filter["v_break_in"];
		$this->break_in->AdvancedSearch->SearchValue2 = @$filter["y_break_in"];
		$this->break_in->AdvancedSearch->SearchOperator2 = @$filter["w_break_in"];
		$this->break_in->AdvancedSearch->Save();

		// Field att_id_break2
		$this->att_id_break2->AdvancedSearch->SearchValue = @$filter["x_att_id_break2"];
		$this->att_id_break2->AdvancedSearch->SearchOperator = @$filter["z_att_id_break2"];
		$this->att_id_break2->AdvancedSearch->SearchCondition = @$filter["v_att_id_break2"];
		$this->att_id_break2->AdvancedSearch->SearchValue2 = @$filter["y_att_id_break2"];
		$this->att_id_break2->AdvancedSearch->SearchOperator2 = @$filter["w_att_id_break2"];
		$this->att_id_break2->AdvancedSearch->Save();

		// Field break_minute
		$this->break_minute->AdvancedSearch->SearchValue = @$filter["x_break_minute"];
		$this->break_minute->AdvancedSearch->SearchOperator = @$filter["z_break_minute"];
		$this->break_minute->AdvancedSearch->SearchCondition = @$filter["v_break_minute"];
		$this->break_minute->AdvancedSearch->SearchValue2 = @$filter["y_break_minute"];
		$this->break_minute->AdvancedSearch->SearchOperator2 = @$filter["w_break_minute"];
		$this->break_minute->AdvancedSearch->Save();

		// Field break
		$this->break->AdvancedSearch->SearchValue = @$filter["x_break"];
		$this->break->AdvancedSearch->SearchOperator = @$filter["z_break"];
		$this->break->AdvancedSearch->SearchCondition = @$filter["v_break"];
		$this->break->AdvancedSearch->SearchValue2 = @$filter["y_break"];
		$this->break->AdvancedSearch->SearchOperator2 = @$filter["w_break"];
		$this->break->AdvancedSearch->Save();

		// Field break_ot_minute
		$this->break_ot_minute->AdvancedSearch->SearchValue = @$filter["x_break_ot_minute"];
		$this->break_ot_minute->AdvancedSearch->SearchOperator = @$filter["z_break_ot_minute"];
		$this->break_ot_minute->AdvancedSearch->SearchCondition = @$filter["v_break_ot_minute"];
		$this->break_ot_minute->AdvancedSearch->SearchValue2 = @$filter["y_break_ot_minute"];
		$this->break_ot_minute->AdvancedSearch->SearchOperator2 = @$filter["w_break_ot_minute"];
		$this->break_ot_minute->AdvancedSearch->Save();

		// Field break_ot
		$this->break_ot->AdvancedSearch->SearchValue = @$filter["x_break_ot"];
		$this->break_ot->AdvancedSearch->SearchOperator = @$filter["z_break_ot"];
		$this->break_ot->AdvancedSearch->SearchCondition = @$filter["v_break_ot"];
		$this->break_ot->AdvancedSearch->SearchValue2 = @$filter["y_break_ot"];
		$this->break_ot->AdvancedSearch->SearchOperator2 = @$filter["w_break_ot"];
		$this->break_ot->AdvancedSearch->Save();

		// Field early_permission
		$this->early_permission->AdvancedSearch->SearchValue = @$filter["x_early_permission"];
		$this->early_permission->AdvancedSearch->SearchOperator = @$filter["z_early_permission"];
		$this->early_permission->AdvancedSearch->SearchCondition = @$filter["v_early_permission"];
		$this->early_permission->AdvancedSearch->SearchValue2 = @$filter["y_early_permission"];
		$this->early_permission->AdvancedSearch->SearchOperator2 = @$filter["w_early_permission"];
		$this->early_permission->AdvancedSearch->Save();

		// Field early_minute
		$this->early_minute->AdvancedSearch->SearchValue = @$filter["x_early_minute"];
		$this->early_minute->AdvancedSearch->SearchOperator = @$filter["z_early_minute"];
		$this->early_minute->AdvancedSearch->SearchCondition = @$filter["v_early_minute"];
		$this->early_minute->AdvancedSearch->SearchValue2 = @$filter["y_early_minute"];
		$this->early_minute->AdvancedSearch->SearchOperator2 = @$filter["w_early_minute"];
		$this->early_minute->AdvancedSearch->Save();

		// Field early
		$this->early->AdvancedSearch->SearchValue = @$filter["x_early"];
		$this->early->AdvancedSearch->SearchOperator = @$filter["z_early"];
		$this->early->AdvancedSearch->SearchCondition = @$filter["v_early"];
		$this->early->AdvancedSearch->SearchValue2 = @$filter["y_early"];
		$this->early->AdvancedSearch->SearchOperator2 = @$filter["w_early"];
		$this->early->AdvancedSearch->Save();

		// Field scan_out
		$this->scan_out->AdvancedSearch->SearchValue = @$filter["x_scan_out"];
		$this->scan_out->AdvancedSearch->SearchOperator = @$filter["z_scan_out"];
		$this->scan_out->AdvancedSearch->SearchCondition = @$filter["v_scan_out"];
		$this->scan_out->AdvancedSearch->SearchValue2 = @$filter["y_scan_out"];
		$this->scan_out->AdvancedSearch->SearchOperator2 = @$filter["w_scan_out"];
		$this->scan_out->AdvancedSearch->Save();

		// Field att_id_out
		$this->att_id_out->AdvancedSearch->SearchValue = @$filter["x_att_id_out"];
		$this->att_id_out->AdvancedSearch->SearchOperator = @$filter["z_att_id_out"];
		$this->att_id_out->AdvancedSearch->SearchCondition = @$filter["v_att_id_out"];
		$this->att_id_out->AdvancedSearch->SearchValue2 = @$filter["y_att_id_out"];
		$this->att_id_out->AdvancedSearch->SearchOperator2 = @$filter["w_att_id_out"];
		$this->att_id_out->AdvancedSearch->Save();

		// Field durasi_minute
		$this->durasi_minute->AdvancedSearch->SearchValue = @$filter["x_durasi_minute"];
		$this->durasi_minute->AdvancedSearch->SearchOperator = @$filter["z_durasi_minute"];
		$this->durasi_minute->AdvancedSearch->SearchCondition = @$filter["v_durasi_minute"];
		$this->durasi_minute->AdvancedSearch->SearchValue2 = @$filter["y_durasi_minute"];
		$this->durasi_minute->AdvancedSearch->SearchOperator2 = @$filter["w_durasi_minute"];
		$this->durasi_minute->AdvancedSearch->Save();

		// Field durasi
		$this->durasi->AdvancedSearch->SearchValue = @$filter["x_durasi"];
		$this->durasi->AdvancedSearch->SearchOperator = @$filter["z_durasi"];
		$this->durasi->AdvancedSearch->SearchCondition = @$filter["v_durasi"];
		$this->durasi->AdvancedSearch->SearchValue2 = @$filter["y_durasi"];
		$this->durasi->AdvancedSearch->SearchOperator2 = @$filter["w_durasi"];
		$this->durasi->AdvancedSearch->Save();

		// Field durasi_eot_minute
		$this->durasi_eot_minute->AdvancedSearch->SearchValue = @$filter["x_durasi_eot_minute"];
		$this->durasi_eot_minute->AdvancedSearch->SearchOperator = @$filter["z_durasi_eot_minute"];
		$this->durasi_eot_minute->AdvancedSearch->SearchCondition = @$filter["v_durasi_eot_minute"];
		$this->durasi_eot_minute->AdvancedSearch->SearchValue2 = @$filter["y_durasi_eot_minute"];
		$this->durasi_eot_minute->AdvancedSearch->SearchOperator2 = @$filter["w_durasi_eot_minute"];
		$this->durasi_eot_minute->AdvancedSearch->Save();

		// Field jk_count_as
		$this->jk_count_as->AdvancedSearch->SearchValue = @$filter["x_jk_count_as"];
		$this->jk_count_as->AdvancedSearch->SearchOperator = @$filter["z_jk_count_as"];
		$this->jk_count_as->AdvancedSearch->SearchCondition = @$filter["v_jk_count_as"];
		$this->jk_count_as->AdvancedSearch->SearchValue2 = @$filter["y_jk_count_as"];
		$this->jk_count_as->AdvancedSearch->SearchOperator2 = @$filter["w_jk_count_as"];
		$this->jk_count_as->AdvancedSearch->Save();

		// Field status_jk
		$this->status_jk->AdvancedSearch->SearchValue = @$filter["x_status_jk"];
		$this->status_jk->AdvancedSearch->SearchOperator = @$filter["z_status_jk"];
		$this->status_jk->AdvancedSearch->SearchCondition = @$filter["v_status_jk"];
		$this->status_jk->AdvancedSearch->SearchValue2 = @$filter["y_status_jk"];
		$this->status_jk->AdvancedSearch->SearchOperator2 = @$filter["w_status_jk"];
		$this->status_jk->AdvancedSearch->Save();

		// Field keterangan
		$this->keterangan->AdvancedSearch->SearchValue = @$filter["x_keterangan"];
		$this->keterangan->AdvancedSearch->SearchOperator = @$filter["z_keterangan"];
		$this->keterangan->AdvancedSearch->SearchCondition = @$filter["v_keterangan"];
		$this->keterangan->AdvancedSearch->SearchValue2 = @$filter["y_keterangan"];
		$this->keterangan->AdvancedSearch->SearchOperator2 = @$filter["w_keterangan"];
		$this->keterangan->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->att_id_in, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->att_id_break1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->att_id_break2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->att_id_out, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->keterangan, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->pegawai_id, $bCtrl); // pegawai_id
			$this->UpdateSort($this->tgl_shift, $bCtrl); // tgl_shift
			$this->UpdateSort($this->khusus_lembur, $bCtrl); // khusus_lembur
			$this->UpdateSort($this->khusus_extra, $bCtrl); // khusus_extra
			$this->UpdateSort($this->temp_id_auto, $bCtrl); // temp_id_auto
			$this->UpdateSort($this->jdw_kerja_m_id, $bCtrl); // jdw_kerja_m_id
			$this->UpdateSort($this->jk_id, $bCtrl); // jk_id
			$this->UpdateSort($this->jns_dok, $bCtrl); // jns_dok
			$this->UpdateSort($this->izin_jenis_id, $bCtrl); // izin_jenis_id
			$this->UpdateSort($this->cuti_n_id, $bCtrl); // cuti_n_id
			$this->UpdateSort($this->libur_umum, $bCtrl); // libur_umum
			$this->UpdateSort($this->libur_rutin, $bCtrl); // libur_rutin
			$this->UpdateSort($this->jk_ot, $bCtrl); // jk_ot
			$this->UpdateSort($this->scan_in, $bCtrl); // scan_in
			$this->UpdateSort($this->att_id_in, $bCtrl); // att_id_in
			$this->UpdateSort($this->late_permission, $bCtrl); // late_permission
			$this->UpdateSort($this->late_minute, $bCtrl); // late_minute
			$this->UpdateSort($this->late, $bCtrl); // late
			$this->UpdateSort($this->break_out, $bCtrl); // break_out
			$this->UpdateSort($this->att_id_break1, $bCtrl); // att_id_break1
			$this->UpdateSort($this->break_in, $bCtrl); // break_in
			$this->UpdateSort($this->att_id_break2, $bCtrl); // att_id_break2
			$this->UpdateSort($this->break_minute, $bCtrl); // break_minute
			$this->UpdateSort($this->break, $bCtrl); // break
			$this->UpdateSort($this->break_ot_minute, $bCtrl); // break_ot_minute
			$this->UpdateSort($this->break_ot, $bCtrl); // break_ot
			$this->UpdateSort($this->early_permission, $bCtrl); // early_permission
			$this->UpdateSort($this->early_minute, $bCtrl); // early_minute
			$this->UpdateSort($this->early, $bCtrl); // early
			$this->UpdateSort($this->scan_out, $bCtrl); // scan_out
			$this->UpdateSort($this->att_id_out, $bCtrl); // att_id_out
			$this->UpdateSort($this->durasi_minute, $bCtrl); // durasi_minute
			$this->UpdateSort($this->durasi, $bCtrl); // durasi
			$this->UpdateSort($this->durasi_eot_minute, $bCtrl); // durasi_eot_minute
			$this->UpdateSort($this->jk_count_as, $bCtrl); // jk_count_as
			$this->UpdateSort($this->status_jk, $bCtrl); // status_jk
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->pegawai_id->setSort("");
				$this->tgl_shift->setSort("");
				$this->khusus_lembur->setSort("");
				$this->khusus_extra->setSort("");
				$this->temp_id_auto->setSort("");
				$this->jdw_kerja_m_id->setSort("");
				$this->jk_id->setSort("");
				$this->jns_dok->setSort("");
				$this->izin_jenis_id->setSort("");
				$this->cuti_n_id->setSort("");
				$this->libur_umum->setSort("");
				$this->libur_rutin->setSort("");
				$this->jk_ot->setSort("");
				$this->scan_in->setSort("");
				$this->att_id_in->setSort("");
				$this->late_permission->setSort("");
				$this->late_minute->setSort("");
				$this->late->setSort("");
				$this->break_out->setSort("");
				$this->att_id_break1->setSort("");
				$this->break_in->setSort("");
				$this->att_id_break2->setSort("");
				$this->break_minute->setSort("");
				$this->break->setSort("");
				$this->break_ot_minute->setSort("");
				$this->break_ot->setSort("");
				$this->early_permission->setSort("");
				$this->early_minute->setSort("");
				$this->early->setSort("");
				$this->scan_out->setSort("");
				$this->att_id_out->setSort("");
				$this->durasi_minute->setSort("");
				$this->durasi->setSort("");
				$this->durasi_eot_minute->setSort("");
				$this->jk_count_as->setSort("");
				$this->status_jk->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->pegawai_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->tgl_shift->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->khusus_lembur->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->khusus_extra->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->temp_id_auto->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fshift_resultlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fshift_resultlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fshift_resultlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fshift_resultlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		$this->tgl_shift->setDbValue($rs->fields('tgl_shift'));
		$this->khusus_lembur->setDbValue($rs->fields('khusus_lembur'));
		$this->khusus_extra->setDbValue($rs->fields('khusus_extra'));
		$this->temp_id_auto->setDbValue($rs->fields('temp_id_auto'));
		$this->jdw_kerja_m_id->setDbValue($rs->fields('jdw_kerja_m_id'));
		$this->jk_id->setDbValue($rs->fields('jk_id'));
		$this->jns_dok->setDbValue($rs->fields('jns_dok'));
		$this->izin_jenis_id->setDbValue($rs->fields('izin_jenis_id'));
		$this->cuti_n_id->setDbValue($rs->fields('cuti_n_id'));
		$this->libur_umum->setDbValue($rs->fields('libur_umum'));
		$this->libur_rutin->setDbValue($rs->fields('libur_rutin'));
		$this->jk_ot->setDbValue($rs->fields('jk_ot'));
		$this->scan_in->setDbValue($rs->fields('scan_in'));
		$this->att_id_in->setDbValue($rs->fields('att_id_in'));
		$this->late_permission->setDbValue($rs->fields('late_permission'));
		$this->late_minute->setDbValue($rs->fields('late_minute'));
		$this->late->setDbValue($rs->fields('late'));
		$this->break_out->setDbValue($rs->fields('break_out'));
		$this->att_id_break1->setDbValue($rs->fields('att_id_break1'));
		$this->break_in->setDbValue($rs->fields('break_in'));
		$this->att_id_break2->setDbValue($rs->fields('att_id_break2'));
		$this->break_minute->setDbValue($rs->fields('break_minute'));
		$this->break->setDbValue($rs->fields('break'));
		$this->break_ot_minute->setDbValue($rs->fields('break_ot_minute'));
		$this->break_ot->setDbValue($rs->fields('break_ot'));
		$this->early_permission->setDbValue($rs->fields('early_permission'));
		$this->early_minute->setDbValue($rs->fields('early_minute'));
		$this->early->setDbValue($rs->fields('early'));
		$this->scan_out->setDbValue($rs->fields('scan_out'));
		$this->att_id_out->setDbValue($rs->fields('att_id_out'));
		$this->durasi_minute->setDbValue($rs->fields('durasi_minute'));
		$this->durasi->setDbValue($rs->fields('durasi'));
		$this->durasi_eot_minute->setDbValue($rs->fields('durasi_eot_minute'));
		$this->jk_count_as->setDbValue($rs->fields('jk_count_as'));
		$this->status_jk->setDbValue($rs->fields('status_jk'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->tgl_shift->DbValue = $row['tgl_shift'];
		$this->khusus_lembur->DbValue = $row['khusus_lembur'];
		$this->khusus_extra->DbValue = $row['khusus_extra'];
		$this->temp_id_auto->DbValue = $row['temp_id_auto'];
		$this->jdw_kerja_m_id->DbValue = $row['jdw_kerja_m_id'];
		$this->jk_id->DbValue = $row['jk_id'];
		$this->jns_dok->DbValue = $row['jns_dok'];
		$this->izin_jenis_id->DbValue = $row['izin_jenis_id'];
		$this->cuti_n_id->DbValue = $row['cuti_n_id'];
		$this->libur_umum->DbValue = $row['libur_umum'];
		$this->libur_rutin->DbValue = $row['libur_rutin'];
		$this->jk_ot->DbValue = $row['jk_ot'];
		$this->scan_in->DbValue = $row['scan_in'];
		$this->att_id_in->DbValue = $row['att_id_in'];
		$this->late_permission->DbValue = $row['late_permission'];
		$this->late_minute->DbValue = $row['late_minute'];
		$this->late->DbValue = $row['late'];
		$this->break_out->DbValue = $row['break_out'];
		$this->att_id_break1->DbValue = $row['att_id_break1'];
		$this->break_in->DbValue = $row['break_in'];
		$this->att_id_break2->DbValue = $row['att_id_break2'];
		$this->break_minute->DbValue = $row['break_minute'];
		$this->break->DbValue = $row['break'];
		$this->break_ot_minute->DbValue = $row['break_ot_minute'];
		$this->break_ot->DbValue = $row['break_ot'];
		$this->early_permission->DbValue = $row['early_permission'];
		$this->early_minute->DbValue = $row['early_minute'];
		$this->early->DbValue = $row['early'];
		$this->scan_out->DbValue = $row['scan_out'];
		$this->att_id_out->DbValue = $row['att_id_out'];
		$this->durasi_minute->DbValue = $row['durasi_minute'];
		$this->durasi->DbValue = $row['durasi'];
		$this->durasi_eot_minute->DbValue = $row['durasi_eot_minute'];
		$this->jk_count_as->DbValue = $row['jk_count_as'];
		$this->status_jk->DbValue = $row['status_jk'];
		$this->keterangan->DbValue = $row['keterangan'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("pegawai_id")) <> "")
			$this->pegawai_id->CurrentValue = $this->getKey("pegawai_id"); // pegawai_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("tgl_shift")) <> "")
			$this->tgl_shift->CurrentValue = $this->getKey("tgl_shift"); // tgl_shift
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("khusus_lembur")) <> "")
			$this->khusus_lembur->CurrentValue = $this->getKey("khusus_lembur"); // khusus_lembur
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("khusus_extra")) <> "")
			$this->khusus_extra->CurrentValue = $this->getKey("khusus_extra"); // khusus_extra
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("temp_id_auto")) <> "")
			$this->temp_id_auto->CurrentValue = $this->getKey("temp_id_auto"); // temp_id_auto
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Convert decimal values if posted back
		if ($this->late->FormValue == $this->late->CurrentValue && is_numeric(ew_StrToFloat($this->late->CurrentValue)))
			$this->late->CurrentValue = ew_StrToFloat($this->late->CurrentValue);

		// Convert decimal values if posted back
		if ($this->break->FormValue == $this->break->CurrentValue && is_numeric(ew_StrToFloat($this->break->CurrentValue)))
			$this->break->CurrentValue = ew_StrToFloat($this->break->CurrentValue);

		// Convert decimal values if posted back
		if ($this->break_ot->FormValue == $this->break_ot->CurrentValue && is_numeric(ew_StrToFloat($this->break_ot->CurrentValue)))
			$this->break_ot->CurrentValue = ew_StrToFloat($this->break_ot->CurrentValue);

		// Convert decimal values if posted back
		if ($this->early->FormValue == $this->early->CurrentValue && is_numeric(ew_StrToFloat($this->early->CurrentValue)))
			$this->early->CurrentValue = ew_StrToFloat($this->early->CurrentValue);

		// Convert decimal values if posted back
		if ($this->durasi->FormValue == $this->durasi->CurrentValue && is_numeric(ew_StrToFloat($this->durasi->CurrentValue)))
			$this->durasi->CurrentValue = ew_StrToFloat($this->durasi->CurrentValue);

		// Convert decimal values if posted back
		if ($this->jk_count_as->FormValue == $this->jk_count_as->CurrentValue && is_numeric(ew_StrToFloat($this->jk_count_as->CurrentValue)))
			$this->jk_count_as->CurrentValue = ew_StrToFloat($this->jk_count_as->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// pegawai_id
		// tgl_shift
		// khusus_lembur
		// khusus_extra
		// temp_id_auto
		// jdw_kerja_m_id
		// jk_id
		// jns_dok
		// izin_jenis_id
		// cuti_n_id
		// libur_umum
		// libur_rutin
		// jk_ot
		// scan_in
		// att_id_in
		// late_permission
		// late_minute
		// late
		// break_out
		// att_id_break1
		// break_in
		// att_id_break2
		// break_minute
		// break
		// break_ot_minute
		// break_ot
		// early_permission
		// early_minute
		// early
		// scan_out
		// att_id_out
		// durasi_minute
		// durasi
		// durasi_eot_minute
		// jk_count_as
		// status_jk
		// keterangan

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// tgl_shift
		$this->tgl_shift->ViewValue = $this->tgl_shift->CurrentValue;
		$this->tgl_shift->ViewValue = ew_FormatDateTime($this->tgl_shift->ViewValue, 0);
		$this->tgl_shift->ViewCustomAttributes = "";

		// khusus_lembur
		$this->khusus_lembur->ViewValue = $this->khusus_lembur->CurrentValue;
		$this->khusus_lembur->ViewCustomAttributes = "";

		// khusus_extra
		$this->khusus_extra->ViewValue = $this->khusus_extra->CurrentValue;
		$this->khusus_extra->ViewCustomAttributes = "";

		// temp_id_auto
		$this->temp_id_auto->ViewValue = $this->temp_id_auto->CurrentValue;
		$this->temp_id_auto->ViewCustomAttributes = "";

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id->ViewValue = $this->jdw_kerja_m_id->CurrentValue;
		$this->jdw_kerja_m_id->ViewCustomAttributes = "";

		// jk_id
		$this->jk_id->ViewValue = $this->jk_id->CurrentValue;
		$this->jk_id->ViewCustomAttributes = "";

		// jns_dok
		$this->jns_dok->ViewValue = $this->jns_dok->CurrentValue;
		$this->jns_dok->ViewCustomAttributes = "";

		// izin_jenis_id
		$this->izin_jenis_id->ViewValue = $this->izin_jenis_id->CurrentValue;
		$this->izin_jenis_id->ViewCustomAttributes = "";

		// cuti_n_id
		$this->cuti_n_id->ViewValue = $this->cuti_n_id->CurrentValue;
		$this->cuti_n_id->ViewCustomAttributes = "";

		// libur_umum
		$this->libur_umum->ViewValue = $this->libur_umum->CurrentValue;
		$this->libur_umum->ViewCustomAttributes = "";

		// libur_rutin
		$this->libur_rutin->ViewValue = $this->libur_rutin->CurrentValue;
		$this->libur_rutin->ViewCustomAttributes = "";

		// jk_ot
		$this->jk_ot->ViewValue = $this->jk_ot->CurrentValue;
		$this->jk_ot->ViewCustomAttributes = "";

		// scan_in
		$this->scan_in->ViewValue = $this->scan_in->CurrentValue;
		$this->scan_in->ViewValue = ew_FormatDateTime($this->scan_in->ViewValue, 0);
		$this->scan_in->ViewCustomAttributes = "";

		// att_id_in
		$this->att_id_in->ViewValue = $this->att_id_in->CurrentValue;
		$this->att_id_in->ViewCustomAttributes = "";

		// late_permission
		$this->late_permission->ViewValue = $this->late_permission->CurrentValue;
		$this->late_permission->ViewCustomAttributes = "";

		// late_minute
		$this->late_minute->ViewValue = $this->late_minute->CurrentValue;
		$this->late_minute->ViewCustomAttributes = "";

		// late
		$this->late->ViewValue = $this->late->CurrentValue;
		$this->late->ViewCustomAttributes = "";

		// break_out
		$this->break_out->ViewValue = $this->break_out->CurrentValue;
		$this->break_out->ViewValue = ew_FormatDateTime($this->break_out->ViewValue, 0);
		$this->break_out->ViewCustomAttributes = "";

		// att_id_break1
		$this->att_id_break1->ViewValue = $this->att_id_break1->CurrentValue;
		$this->att_id_break1->ViewCustomAttributes = "";

		// break_in
		$this->break_in->ViewValue = $this->break_in->CurrentValue;
		$this->break_in->ViewValue = ew_FormatDateTime($this->break_in->ViewValue, 0);
		$this->break_in->ViewCustomAttributes = "";

		// att_id_break2
		$this->att_id_break2->ViewValue = $this->att_id_break2->CurrentValue;
		$this->att_id_break2->ViewCustomAttributes = "";

		// break_minute
		$this->break_minute->ViewValue = $this->break_minute->CurrentValue;
		$this->break_minute->ViewCustomAttributes = "";

		// break
		$this->break->ViewValue = $this->break->CurrentValue;
		$this->break->ViewCustomAttributes = "";

		// break_ot_minute
		$this->break_ot_minute->ViewValue = $this->break_ot_minute->CurrentValue;
		$this->break_ot_minute->ViewCustomAttributes = "";

		// break_ot
		$this->break_ot->ViewValue = $this->break_ot->CurrentValue;
		$this->break_ot->ViewCustomAttributes = "";

		// early_permission
		$this->early_permission->ViewValue = $this->early_permission->CurrentValue;
		$this->early_permission->ViewCustomAttributes = "";

		// early_minute
		$this->early_minute->ViewValue = $this->early_minute->CurrentValue;
		$this->early_minute->ViewCustomAttributes = "";

		// early
		$this->early->ViewValue = $this->early->CurrentValue;
		$this->early->ViewCustomAttributes = "";

		// scan_out
		$this->scan_out->ViewValue = $this->scan_out->CurrentValue;
		$this->scan_out->ViewValue = ew_FormatDateTime($this->scan_out->ViewValue, 0);
		$this->scan_out->ViewCustomAttributes = "";

		// att_id_out
		$this->att_id_out->ViewValue = $this->att_id_out->CurrentValue;
		$this->att_id_out->ViewCustomAttributes = "";

		// durasi_minute
		$this->durasi_minute->ViewValue = $this->durasi_minute->CurrentValue;
		$this->durasi_minute->ViewCustomAttributes = "";

		// durasi
		$this->durasi->ViewValue = $this->durasi->CurrentValue;
		$this->durasi->ViewCustomAttributes = "";

		// durasi_eot_minute
		$this->durasi_eot_minute->ViewValue = $this->durasi_eot_minute->CurrentValue;
		$this->durasi_eot_minute->ViewCustomAttributes = "";

		// jk_count_as
		$this->jk_count_as->ViewValue = $this->jk_count_as->CurrentValue;
		$this->jk_count_as->ViewCustomAttributes = "";

		// status_jk
		$this->status_jk->ViewValue = $this->status_jk->CurrentValue;
		$this->status_jk->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

			// tgl_shift
			$this->tgl_shift->LinkCustomAttributes = "";
			$this->tgl_shift->HrefValue = "";
			$this->tgl_shift->TooltipValue = "";

			// khusus_lembur
			$this->khusus_lembur->LinkCustomAttributes = "";
			$this->khusus_lembur->HrefValue = "";
			$this->khusus_lembur->TooltipValue = "";

			// khusus_extra
			$this->khusus_extra->LinkCustomAttributes = "";
			$this->khusus_extra->HrefValue = "";
			$this->khusus_extra->TooltipValue = "";

			// temp_id_auto
			$this->temp_id_auto->LinkCustomAttributes = "";
			$this->temp_id_auto->HrefValue = "";
			$this->temp_id_auto->TooltipValue = "";

			// jdw_kerja_m_id
			$this->jdw_kerja_m_id->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id->HrefValue = "";
			$this->jdw_kerja_m_id->TooltipValue = "";

			// jk_id
			$this->jk_id->LinkCustomAttributes = "";
			$this->jk_id->HrefValue = "";
			$this->jk_id->TooltipValue = "";

			// jns_dok
			$this->jns_dok->LinkCustomAttributes = "";
			$this->jns_dok->HrefValue = "";
			$this->jns_dok->TooltipValue = "";

			// izin_jenis_id
			$this->izin_jenis_id->LinkCustomAttributes = "";
			$this->izin_jenis_id->HrefValue = "";
			$this->izin_jenis_id->TooltipValue = "";

			// cuti_n_id
			$this->cuti_n_id->LinkCustomAttributes = "";
			$this->cuti_n_id->HrefValue = "";
			$this->cuti_n_id->TooltipValue = "";

			// libur_umum
			$this->libur_umum->LinkCustomAttributes = "";
			$this->libur_umum->HrefValue = "";
			$this->libur_umum->TooltipValue = "";

			// libur_rutin
			$this->libur_rutin->LinkCustomAttributes = "";
			$this->libur_rutin->HrefValue = "";
			$this->libur_rutin->TooltipValue = "";

			// jk_ot
			$this->jk_ot->LinkCustomAttributes = "";
			$this->jk_ot->HrefValue = "";
			$this->jk_ot->TooltipValue = "";

			// scan_in
			$this->scan_in->LinkCustomAttributes = "";
			$this->scan_in->HrefValue = "";
			$this->scan_in->TooltipValue = "";

			// att_id_in
			$this->att_id_in->LinkCustomAttributes = "";
			$this->att_id_in->HrefValue = "";
			$this->att_id_in->TooltipValue = "";

			// late_permission
			$this->late_permission->LinkCustomAttributes = "";
			$this->late_permission->HrefValue = "";
			$this->late_permission->TooltipValue = "";

			// late_minute
			$this->late_minute->LinkCustomAttributes = "";
			$this->late_minute->HrefValue = "";
			$this->late_minute->TooltipValue = "";

			// late
			$this->late->LinkCustomAttributes = "";
			$this->late->HrefValue = "";
			$this->late->TooltipValue = "";

			// break_out
			$this->break_out->LinkCustomAttributes = "";
			$this->break_out->HrefValue = "";
			$this->break_out->TooltipValue = "";

			// att_id_break1
			$this->att_id_break1->LinkCustomAttributes = "";
			$this->att_id_break1->HrefValue = "";
			$this->att_id_break1->TooltipValue = "";

			// break_in
			$this->break_in->LinkCustomAttributes = "";
			$this->break_in->HrefValue = "";
			$this->break_in->TooltipValue = "";

			// att_id_break2
			$this->att_id_break2->LinkCustomAttributes = "";
			$this->att_id_break2->HrefValue = "";
			$this->att_id_break2->TooltipValue = "";

			// break_minute
			$this->break_minute->LinkCustomAttributes = "";
			$this->break_minute->HrefValue = "";
			$this->break_minute->TooltipValue = "";

			// break
			$this->break->LinkCustomAttributes = "";
			$this->break->HrefValue = "";
			$this->break->TooltipValue = "";

			// break_ot_minute
			$this->break_ot_minute->LinkCustomAttributes = "";
			$this->break_ot_minute->HrefValue = "";
			$this->break_ot_minute->TooltipValue = "";

			// break_ot
			$this->break_ot->LinkCustomAttributes = "";
			$this->break_ot->HrefValue = "";
			$this->break_ot->TooltipValue = "";

			// early_permission
			$this->early_permission->LinkCustomAttributes = "";
			$this->early_permission->HrefValue = "";
			$this->early_permission->TooltipValue = "";

			// early_minute
			$this->early_minute->LinkCustomAttributes = "";
			$this->early_minute->HrefValue = "";
			$this->early_minute->TooltipValue = "";

			// early
			$this->early->LinkCustomAttributes = "";
			$this->early->HrefValue = "";
			$this->early->TooltipValue = "";

			// scan_out
			$this->scan_out->LinkCustomAttributes = "";
			$this->scan_out->HrefValue = "";
			$this->scan_out->TooltipValue = "";

			// att_id_out
			$this->att_id_out->LinkCustomAttributes = "";
			$this->att_id_out->HrefValue = "";
			$this->att_id_out->TooltipValue = "";

			// durasi_minute
			$this->durasi_minute->LinkCustomAttributes = "";
			$this->durasi_minute->HrefValue = "";
			$this->durasi_minute->TooltipValue = "";

			// durasi
			$this->durasi->LinkCustomAttributes = "";
			$this->durasi->HrefValue = "";
			$this->durasi->TooltipValue = "";

			// durasi_eot_minute
			$this->durasi_eot_minute->LinkCustomAttributes = "";
			$this->durasi_eot_minute->HrefValue = "";
			$this->durasi_eot_minute->TooltipValue = "";

			// jk_count_as
			$this->jk_count_as->LinkCustomAttributes = "";
			$this->jk_count_as->HrefValue = "";
			$this->jk_count_as->TooltipValue = "";

			// status_jk
			$this->status_jk->LinkCustomAttributes = "";
			$this->status_jk->HrefValue = "";
			$this->status_jk->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_shift_result\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_shift_result',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fshift_resultlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];
		$sContentType = @$_POST["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= ew_CleanEmailContent($EmailContent); // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-danger\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Build QueryString for search
		if ($this->BasicSearch->getKeyword() <> "") {
			$sQry .= "&" . EW_TABLE_BASIC_SEARCH . "=" . urlencode($this->BasicSearch->getKeyword()) . "&" . EW_TABLE_BASIC_SEARCH_TYPE . "=" . urlencode($this->BasicSearch->getType());
		}

		// Build QueryString for pager
		$sQry .= "&" . EW_TABLE_REC_PER_PAGE . "=" . urlencode($this->getRecordsPerPage()) . "&" . EW_TABLE_START_REC . "=" . urlencode($this->getStartRecordNumber());
		return $sQry;
	}

	// Add search QueryString
	function AddSearchQueryString(&$Qry, &$Fld) {
		$FldSearchValue = $Fld->AdvancedSearch->getValue("x");
		$FldParm = substr($Fld->FldVar,2);
		if (strval($FldSearchValue) <> "") {
			$Qry .= "&x_" . $FldParm . "=" . urlencode($FldSearchValue) .
				"&z_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("z"));
		}
		$FldSearchValue2 = $Fld->AdvancedSearch->getValue("y");
		if (strval($FldSearchValue2) <> "") {
			$Qry .= "&v_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("v")) .
				"&y_" . $FldParm . "=" . urlencode($FldSearchValue2) .
				"&w_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("w"));
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($shift_result_list)) $shift_result_list = new cshift_result_list();

// Page init
$shift_result_list->Page_Init();

// Page main
$shift_result_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$shift_result_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($shift_result->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fshift_resultlist = new ew_Form("fshift_resultlist", "list");
fshift_resultlist.FormKeyCountName = '<?php echo $shift_result_list->FormKeyCountName ?>';

// Form_CustomValidate event
fshift_resultlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fshift_resultlist.ValidateRequired = true;
<?php } else { ?>
fshift_resultlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fshift_resultlistsrch = new ew_Form("fshift_resultlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($shift_result->Export == "") { ?>
<div class="ewToolbar">
<?php if ($shift_result->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($shift_result_list->TotalRecs > 0 && $shift_result_list->ExportOptions->Visible()) { ?>
<?php $shift_result_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($shift_result_list->SearchOptions->Visible()) { ?>
<?php $shift_result_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($shift_result_list->FilterOptions->Visible()) { ?>
<?php $shift_result_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($shift_result->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $shift_result_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($shift_result_list->TotalRecs <= 0)
			$shift_result_list->TotalRecs = $shift_result->SelectRecordCount();
	} else {
		if (!$shift_result_list->Recordset && ($shift_result_list->Recordset = $shift_result_list->LoadRecordset()))
			$shift_result_list->TotalRecs = $shift_result_list->Recordset->RecordCount();
	}
	$shift_result_list->StartRec = 1;
	if ($shift_result_list->DisplayRecs <= 0 || ($shift_result->Export <> "" && $shift_result->ExportAll)) // Display all records
		$shift_result_list->DisplayRecs = $shift_result_list->TotalRecs;
	if (!($shift_result->Export <> "" && $shift_result->ExportAll))
		$shift_result_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$shift_result_list->Recordset = $shift_result_list->LoadRecordset($shift_result_list->StartRec-1, $shift_result_list->DisplayRecs);

	// Set no record found message
	if ($shift_result->CurrentAction == "" && $shift_result_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$shift_result_list->setWarningMessage(ew_DeniedMsg());
		if ($shift_result_list->SearchWhere == "0=101")
			$shift_result_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$shift_result_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$shift_result_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($shift_result->Export == "" && $shift_result->CurrentAction == "") { ?>
<form name="fshift_resultlistsrch" id="fshift_resultlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($shift_result_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fshift_resultlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="shift_result">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($shift_result_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($shift_result_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $shift_result_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($shift_result_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($shift_result_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($shift_result_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($shift_result_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $shift_result_list->ShowPageHeader(); ?>
<?php
$shift_result_list->ShowMessage();
?>
<?php if ($shift_result_list->TotalRecs > 0 || $shift_result->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid shift_result">
<form name="fshift_resultlist" id="fshift_resultlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($shift_result_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $shift_result_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="shift_result">
<div id="gmp_shift_result" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($shift_result_list->TotalRecs > 0 || $shift_result->CurrentAction == "gridedit") { ?>
<table id="tbl_shift_resultlist" class="table ewTable">
<?php echo $shift_result->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$shift_result_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$shift_result_list->RenderListOptions();

// Render list options (header, left)
$shift_result_list->ListOptions->Render("header", "left");
?>
<?php if ($shift_result->pegawai_id->Visible) { // pegawai_id ?>
	<?php if ($shift_result->SortUrl($shift_result->pegawai_id) == "") { ?>
		<th data-name="pegawai_id"><div id="elh_shift_result_pegawai_id" class="shift_result_pegawai_id"><div class="ewTableHeaderCaption"><?php echo $shift_result->pegawai_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->pegawai_id) ?>',2);"><div id="elh_shift_result_pegawai_id" class="shift_result_pegawai_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->pegawai_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->pegawai_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->pegawai_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->tgl_shift->Visible) { // tgl_shift ?>
	<?php if ($shift_result->SortUrl($shift_result->tgl_shift) == "") { ?>
		<th data-name="tgl_shift"><div id="elh_shift_result_tgl_shift" class="shift_result_tgl_shift"><div class="ewTableHeaderCaption"><?php echo $shift_result->tgl_shift->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_shift"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->tgl_shift) ?>',2);"><div id="elh_shift_result_tgl_shift" class="shift_result_tgl_shift">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->tgl_shift->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->tgl_shift->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->tgl_shift->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->khusus_lembur->Visible) { // khusus_lembur ?>
	<?php if ($shift_result->SortUrl($shift_result->khusus_lembur) == "") { ?>
		<th data-name="khusus_lembur"><div id="elh_shift_result_khusus_lembur" class="shift_result_khusus_lembur"><div class="ewTableHeaderCaption"><?php echo $shift_result->khusus_lembur->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="khusus_lembur"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->khusus_lembur) ?>',2);"><div id="elh_shift_result_khusus_lembur" class="shift_result_khusus_lembur">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->khusus_lembur->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->khusus_lembur->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->khusus_lembur->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->khusus_extra->Visible) { // khusus_extra ?>
	<?php if ($shift_result->SortUrl($shift_result->khusus_extra) == "") { ?>
		<th data-name="khusus_extra"><div id="elh_shift_result_khusus_extra" class="shift_result_khusus_extra"><div class="ewTableHeaderCaption"><?php echo $shift_result->khusus_extra->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="khusus_extra"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->khusus_extra) ?>',2);"><div id="elh_shift_result_khusus_extra" class="shift_result_khusus_extra">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->khusus_extra->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->khusus_extra->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->khusus_extra->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->temp_id_auto->Visible) { // temp_id_auto ?>
	<?php if ($shift_result->SortUrl($shift_result->temp_id_auto) == "") { ?>
		<th data-name="temp_id_auto"><div id="elh_shift_result_temp_id_auto" class="shift_result_temp_id_auto"><div class="ewTableHeaderCaption"><?php echo $shift_result->temp_id_auto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="temp_id_auto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->temp_id_auto) ?>',2);"><div id="elh_shift_result_temp_id_auto" class="shift_result_temp_id_auto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->temp_id_auto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->temp_id_auto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->temp_id_auto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->jdw_kerja_m_id->Visible) { // jdw_kerja_m_id ?>
	<?php if ($shift_result->SortUrl($shift_result->jdw_kerja_m_id) == "") { ?>
		<th data-name="jdw_kerja_m_id"><div id="elh_shift_result_jdw_kerja_m_id" class="shift_result_jdw_kerja_m_id"><div class="ewTableHeaderCaption"><?php echo $shift_result->jdw_kerja_m_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jdw_kerja_m_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->jdw_kerja_m_id) ?>',2);"><div id="elh_shift_result_jdw_kerja_m_id" class="shift_result_jdw_kerja_m_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->jdw_kerja_m_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->jdw_kerja_m_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->jdw_kerja_m_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->jk_id->Visible) { // jk_id ?>
	<?php if ($shift_result->SortUrl($shift_result->jk_id) == "") { ?>
		<th data-name="jk_id"><div id="elh_shift_result_jk_id" class="shift_result_jk_id"><div class="ewTableHeaderCaption"><?php echo $shift_result->jk_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->jk_id) ?>',2);"><div id="elh_shift_result_jk_id" class="shift_result_jk_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->jk_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->jk_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->jk_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->jns_dok->Visible) { // jns_dok ?>
	<?php if ($shift_result->SortUrl($shift_result->jns_dok) == "") { ?>
		<th data-name="jns_dok"><div id="elh_shift_result_jns_dok" class="shift_result_jns_dok"><div class="ewTableHeaderCaption"><?php echo $shift_result->jns_dok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jns_dok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->jns_dok) ?>',2);"><div id="elh_shift_result_jns_dok" class="shift_result_jns_dok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->jns_dok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->jns_dok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->jns_dok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->izin_jenis_id->Visible) { // izin_jenis_id ?>
	<?php if ($shift_result->SortUrl($shift_result->izin_jenis_id) == "") { ?>
		<th data-name="izin_jenis_id"><div id="elh_shift_result_izin_jenis_id" class="shift_result_izin_jenis_id"><div class="ewTableHeaderCaption"><?php echo $shift_result->izin_jenis_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="izin_jenis_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->izin_jenis_id) ?>',2);"><div id="elh_shift_result_izin_jenis_id" class="shift_result_izin_jenis_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->izin_jenis_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->izin_jenis_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->izin_jenis_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->cuti_n_id->Visible) { // cuti_n_id ?>
	<?php if ($shift_result->SortUrl($shift_result->cuti_n_id) == "") { ?>
		<th data-name="cuti_n_id"><div id="elh_shift_result_cuti_n_id" class="shift_result_cuti_n_id"><div class="ewTableHeaderCaption"><?php echo $shift_result->cuti_n_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cuti_n_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->cuti_n_id) ?>',2);"><div id="elh_shift_result_cuti_n_id" class="shift_result_cuti_n_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->cuti_n_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->cuti_n_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->cuti_n_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->libur_umum->Visible) { // libur_umum ?>
	<?php if ($shift_result->SortUrl($shift_result->libur_umum) == "") { ?>
		<th data-name="libur_umum"><div id="elh_shift_result_libur_umum" class="shift_result_libur_umum"><div class="ewTableHeaderCaption"><?php echo $shift_result->libur_umum->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="libur_umum"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->libur_umum) ?>',2);"><div id="elh_shift_result_libur_umum" class="shift_result_libur_umum">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->libur_umum->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->libur_umum->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->libur_umum->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->libur_rutin->Visible) { // libur_rutin ?>
	<?php if ($shift_result->SortUrl($shift_result->libur_rutin) == "") { ?>
		<th data-name="libur_rutin"><div id="elh_shift_result_libur_rutin" class="shift_result_libur_rutin"><div class="ewTableHeaderCaption"><?php echo $shift_result->libur_rutin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="libur_rutin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->libur_rutin) ?>',2);"><div id="elh_shift_result_libur_rutin" class="shift_result_libur_rutin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->libur_rutin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->libur_rutin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->libur_rutin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->jk_ot->Visible) { // jk_ot ?>
	<?php if ($shift_result->SortUrl($shift_result->jk_ot) == "") { ?>
		<th data-name="jk_ot"><div id="elh_shift_result_jk_ot" class="shift_result_jk_ot"><div class="ewTableHeaderCaption"><?php echo $shift_result->jk_ot->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_ot"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->jk_ot) ?>',2);"><div id="elh_shift_result_jk_ot" class="shift_result_jk_ot">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->jk_ot->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->jk_ot->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->jk_ot->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->scan_in->Visible) { // scan_in ?>
	<?php if ($shift_result->SortUrl($shift_result->scan_in) == "") { ?>
		<th data-name="scan_in"><div id="elh_shift_result_scan_in" class="shift_result_scan_in"><div class="ewTableHeaderCaption"><?php echo $shift_result->scan_in->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="scan_in"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->scan_in) ?>',2);"><div id="elh_shift_result_scan_in" class="shift_result_scan_in">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->scan_in->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->scan_in->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->scan_in->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->att_id_in->Visible) { // att_id_in ?>
	<?php if ($shift_result->SortUrl($shift_result->att_id_in) == "") { ?>
		<th data-name="att_id_in"><div id="elh_shift_result_att_id_in" class="shift_result_att_id_in"><div class="ewTableHeaderCaption"><?php echo $shift_result->att_id_in->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="att_id_in"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->att_id_in) ?>',2);"><div id="elh_shift_result_att_id_in" class="shift_result_att_id_in">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->att_id_in->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->att_id_in->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->att_id_in->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->late_permission->Visible) { // late_permission ?>
	<?php if ($shift_result->SortUrl($shift_result->late_permission) == "") { ?>
		<th data-name="late_permission"><div id="elh_shift_result_late_permission" class="shift_result_late_permission"><div class="ewTableHeaderCaption"><?php echo $shift_result->late_permission->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="late_permission"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->late_permission) ?>',2);"><div id="elh_shift_result_late_permission" class="shift_result_late_permission">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->late_permission->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->late_permission->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->late_permission->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->late_minute->Visible) { // late_minute ?>
	<?php if ($shift_result->SortUrl($shift_result->late_minute) == "") { ?>
		<th data-name="late_minute"><div id="elh_shift_result_late_minute" class="shift_result_late_minute"><div class="ewTableHeaderCaption"><?php echo $shift_result->late_minute->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="late_minute"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->late_minute) ?>',2);"><div id="elh_shift_result_late_minute" class="shift_result_late_minute">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->late_minute->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->late_minute->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->late_minute->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->late->Visible) { // late ?>
	<?php if ($shift_result->SortUrl($shift_result->late) == "") { ?>
		<th data-name="late"><div id="elh_shift_result_late" class="shift_result_late"><div class="ewTableHeaderCaption"><?php echo $shift_result->late->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="late"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->late) ?>',2);"><div id="elh_shift_result_late" class="shift_result_late">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->late->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->late->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->late->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->break_out->Visible) { // break_out ?>
	<?php if ($shift_result->SortUrl($shift_result->break_out) == "") { ?>
		<th data-name="break_out"><div id="elh_shift_result_break_out" class="shift_result_break_out"><div class="ewTableHeaderCaption"><?php echo $shift_result->break_out->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="break_out"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->break_out) ?>',2);"><div id="elh_shift_result_break_out" class="shift_result_break_out">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->break_out->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->break_out->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->break_out->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->att_id_break1->Visible) { // att_id_break1 ?>
	<?php if ($shift_result->SortUrl($shift_result->att_id_break1) == "") { ?>
		<th data-name="att_id_break1"><div id="elh_shift_result_att_id_break1" class="shift_result_att_id_break1"><div class="ewTableHeaderCaption"><?php echo $shift_result->att_id_break1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="att_id_break1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->att_id_break1) ?>',2);"><div id="elh_shift_result_att_id_break1" class="shift_result_att_id_break1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->att_id_break1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->att_id_break1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->att_id_break1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->break_in->Visible) { // break_in ?>
	<?php if ($shift_result->SortUrl($shift_result->break_in) == "") { ?>
		<th data-name="break_in"><div id="elh_shift_result_break_in" class="shift_result_break_in"><div class="ewTableHeaderCaption"><?php echo $shift_result->break_in->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="break_in"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->break_in) ?>',2);"><div id="elh_shift_result_break_in" class="shift_result_break_in">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->break_in->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->break_in->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->break_in->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->att_id_break2->Visible) { // att_id_break2 ?>
	<?php if ($shift_result->SortUrl($shift_result->att_id_break2) == "") { ?>
		<th data-name="att_id_break2"><div id="elh_shift_result_att_id_break2" class="shift_result_att_id_break2"><div class="ewTableHeaderCaption"><?php echo $shift_result->att_id_break2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="att_id_break2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->att_id_break2) ?>',2);"><div id="elh_shift_result_att_id_break2" class="shift_result_att_id_break2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->att_id_break2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->att_id_break2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->att_id_break2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->break_minute->Visible) { // break_minute ?>
	<?php if ($shift_result->SortUrl($shift_result->break_minute) == "") { ?>
		<th data-name="break_minute"><div id="elh_shift_result_break_minute" class="shift_result_break_minute"><div class="ewTableHeaderCaption"><?php echo $shift_result->break_minute->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="break_minute"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->break_minute) ?>',2);"><div id="elh_shift_result_break_minute" class="shift_result_break_minute">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->break_minute->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->break_minute->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->break_minute->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->break->Visible) { // break ?>
	<?php if ($shift_result->SortUrl($shift_result->break) == "") { ?>
		<th data-name="break"><div id="elh_shift_result_break" class="shift_result_break"><div class="ewTableHeaderCaption"><?php echo $shift_result->break->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="break"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->break) ?>',2);"><div id="elh_shift_result_break" class="shift_result_break">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->break->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->break->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->break->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->break_ot_minute->Visible) { // break_ot_minute ?>
	<?php if ($shift_result->SortUrl($shift_result->break_ot_minute) == "") { ?>
		<th data-name="break_ot_minute"><div id="elh_shift_result_break_ot_minute" class="shift_result_break_ot_minute"><div class="ewTableHeaderCaption"><?php echo $shift_result->break_ot_minute->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="break_ot_minute"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->break_ot_minute) ?>',2);"><div id="elh_shift_result_break_ot_minute" class="shift_result_break_ot_minute">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->break_ot_minute->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->break_ot_minute->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->break_ot_minute->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->break_ot->Visible) { // break_ot ?>
	<?php if ($shift_result->SortUrl($shift_result->break_ot) == "") { ?>
		<th data-name="break_ot"><div id="elh_shift_result_break_ot" class="shift_result_break_ot"><div class="ewTableHeaderCaption"><?php echo $shift_result->break_ot->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="break_ot"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->break_ot) ?>',2);"><div id="elh_shift_result_break_ot" class="shift_result_break_ot">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->break_ot->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->break_ot->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->break_ot->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->early_permission->Visible) { // early_permission ?>
	<?php if ($shift_result->SortUrl($shift_result->early_permission) == "") { ?>
		<th data-name="early_permission"><div id="elh_shift_result_early_permission" class="shift_result_early_permission"><div class="ewTableHeaderCaption"><?php echo $shift_result->early_permission->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="early_permission"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->early_permission) ?>',2);"><div id="elh_shift_result_early_permission" class="shift_result_early_permission">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->early_permission->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->early_permission->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->early_permission->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->early_minute->Visible) { // early_minute ?>
	<?php if ($shift_result->SortUrl($shift_result->early_minute) == "") { ?>
		<th data-name="early_minute"><div id="elh_shift_result_early_minute" class="shift_result_early_minute"><div class="ewTableHeaderCaption"><?php echo $shift_result->early_minute->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="early_minute"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->early_minute) ?>',2);"><div id="elh_shift_result_early_minute" class="shift_result_early_minute">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->early_minute->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->early_minute->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->early_minute->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->early->Visible) { // early ?>
	<?php if ($shift_result->SortUrl($shift_result->early) == "") { ?>
		<th data-name="early"><div id="elh_shift_result_early" class="shift_result_early"><div class="ewTableHeaderCaption"><?php echo $shift_result->early->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="early"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->early) ?>',2);"><div id="elh_shift_result_early" class="shift_result_early">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->early->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->early->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->early->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->scan_out->Visible) { // scan_out ?>
	<?php if ($shift_result->SortUrl($shift_result->scan_out) == "") { ?>
		<th data-name="scan_out"><div id="elh_shift_result_scan_out" class="shift_result_scan_out"><div class="ewTableHeaderCaption"><?php echo $shift_result->scan_out->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="scan_out"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->scan_out) ?>',2);"><div id="elh_shift_result_scan_out" class="shift_result_scan_out">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->scan_out->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->scan_out->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->scan_out->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->att_id_out->Visible) { // att_id_out ?>
	<?php if ($shift_result->SortUrl($shift_result->att_id_out) == "") { ?>
		<th data-name="att_id_out"><div id="elh_shift_result_att_id_out" class="shift_result_att_id_out"><div class="ewTableHeaderCaption"><?php echo $shift_result->att_id_out->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="att_id_out"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->att_id_out) ?>',2);"><div id="elh_shift_result_att_id_out" class="shift_result_att_id_out">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->att_id_out->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->att_id_out->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->att_id_out->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->durasi_minute->Visible) { // durasi_minute ?>
	<?php if ($shift_result->SortUrl($shift_result->durasi_minute) == "") { ?>
		<th data-name="durasi_minute"><div id="elh_shift_result_durasi_minute" class="shift_result_durasi_minute"><div class="ewTableHeaderCaption"><?php echo $shift_result->durasi_minute->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="durasi_minute"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->durasi_minute) ?>',2);"><div id="elh_shift_result_durasi_minute" class="shift_result_durasi_minute">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->durasi_minute->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->durasi_minute->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->durasi_minute->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->durasi->Visible) { // durasi ?>
	<?php if ($shift_result->SortUrl($shift_result->durasi) == "") { ?>
		<th data-name="durasi"><div id="elh_shift_result_durasi" class="shift_result_durasi"><div class="ewTableHeaderCaption"><?php echo $shift_result->durasi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="durasi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->durasi) ?>',2);"><div id="elh_shift_result_durasi" class="shift_result_durasi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->durasi->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->durasi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->durasi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->durasi_eot_minute->Visible) { // durasi_eot_minute ?>
	<?php if ($shift_result->SortUrl($shift_result->durasi_eot_minute) == "") { ?>
		<th data-name="durasi_eot_minute"><div id="elh_shift_result_durasi_eot_minute" class="shift_result_durasi_eot_minute"><div class="ewTableHeaderCaption"><?php echo $shift_result->durasi_eot_minute->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="durasi_eot_minute"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->durasi_eot_minute) ?>',2);"><div id="elh_shift_result_durasi_eot_minute" class="shift_result_durasi_eot_minute">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->durasi_eot_minute->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->durasi_eot_minute->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->durasi_eot_minute->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->jk_count_as->Visible) { // jk_count_as ?>
	<?php if ($shift_result->SortUrl($shift_result->jk_count_as) == "") { ?>
		<th data-name="jk_count_as"><div id="elh_shift_result_jk_count_as" class="shift_result_jk_count_as"><div class="ewTableHeaderCaption"><?php echo $shift_result->jk_count_as->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_count_as"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->jk_count_as) ?>',2);"><div id="elh_shift_result_jk_count_as" class="shift_result_jk_count_as">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->jk_count_as->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->jk_count_as->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->jk_count_as->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($shift_result->status_jk->Visible) { // status_jk ?>
	<?php if ($shift_result->SortUrl($shift_result->status_jk) == "") { ?>
		<th data-name="status_jk"><div id="elh_shift_result_status_jk" class="shift_result_status_jk"><div class="ewTableHeaderCaption"><?php echo $shift_result->status_jk->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status_jk"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $shift_result->SortUrl($shift_result->status_jk) ?>',2);"><div id="elh_shift_result_status_jk" class="shift_result_status_jk">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $shift_result->status_jk->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($shift_result->status_jk->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($shift_result->status_jk->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$shift_result_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($shift_result->ExportAll && $shift_result->Export <> "") {
	$shift_result_list->StopRec = $shift_result_list->TotalRecs;
} else {

	// Set the last record to display
	if ($shift_result_list->TotalRecs > $shift_result_list->StartRec + $shift_result_list->DisplayRecs - 1)
		$shift_result_list->StopRec = $shift_result_list->StartRec + $shift_result_list->DisplayRecs - 1;
	else
		$shift_result_list->StopRec = $shift_result_list->TotalRecs;
}
$shift_result_list->RecCnt = $shift_result_list->StartRec - 1;
if ($shift_result_list->Recordset && !$shift_result_list->Recordset->EOF) {
	$shift_result_list->Recordset->MoveFirst();
	$bSelectLimit = $shift_result_list->UseSelectLimit;
	if (!$bSelectLimit && $shift_result_list->StartRec > 1)
		$shift_result_list->Recordset->Move($shift_result_list->StartRec - 1);
} elseif (!$shift_result->AllowAddDeleteRow && $shift_result_list->StopRec == 0) {
	$shift_result_list->StopRec = $shift_result->GridAddRowCount;
}

// Initialize aggregate
$shift_result->RowType = EW_ROWTYPE_AGGREGATEINIT;
$shift_result->ResetAttrs();
$shift_result_list->RenderRow();
while ($shift_result_list->RecCnt < $shift_result_list->StopRec) {
	$shift_result_list->RecCnt++;
	if (intval($shift_result_list->RecCnt) >= intval($shift_result_list->StartRec)) {
		$shift_result_list->RowCnt++;

		// Set up key count
		$shift_result_list->KeyCount = $shift_result_list->RowIndex;

		// Init row class and style
		$shift_result->ResetAttrs();
		$shift_result->CssClass = "";
		if ($shift_result->CurrentAction == "gridadd") {
		} else {
			$shift_result_list->LoadRowValues($shift_result_list->Recordset); // Load row values
		}
		$shift_result->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$shift_result->RowAttrs = array_merge($shift_result->RowAttrs, array('data-rowindex'=>$shift_result_list->RowCnt, 'id'=>'r' . $shift_result_list->RowCnt . '_shift_result', 'data-rowtype'=>$shift_result->RowType));

		// Render row
		$shift_result_list->RenderRow();

		// Render list options
		$shift_result_list->RenderListOptions();
?>
	<tr<?php echo $shift_result->RowAttributes() ?>>
<?php

// Render list options (body, left)
$shift_result_list->ListOptions->Render("body", "left", $shift_result_list->RowCnt);
?>
	<?php if ($shift_result->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id"<?php echo $shift_result->pegawai_id->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_pegawai_id" class="shift_result_pegawai_id">
<span<?php echo $shift_result->pegawai_id->ViewAttributes() ?>>
<?php echo $shift_result->pegawai_id->ListViewValue() ?></span>
</span>
<a id="<?php echo $shift_result_list->PageObjName . "_row_" . $shift_result_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($shift_result->tgl_shift->Visible) { // tgl_shift ?>
		<td data-name="tgl_shift"<?php echo $shift_result->tgl_shift->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_tgl_shift" class="shift_result_tgl_shift">
<span<?php echo $shift_result->tgl_shift->ViewAttributes() ?>>
<?php echo $shift_result->tgl_shift->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->khusus_lembur->Visible) { // khusus_lembur ?>
		<td data-name="khusus_lembur"<?php echo $shift_result->khusus_lembur->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_khusus_lembur" class="shift_result_khusus_lembur">
<span<?php echo $shift_result->khusus_lembur->ViewAttributes() ?>>
<?php echo $shift_result->khusus_lembur->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->khusus_extra->Visible) { // khusus_extra ?>
		<td data-name="khusus_extra"<?php echo $shift_result->khusus_extra->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_khusus_extra" class="shift_result_khusus_extra">
<span<?php echo $shift_result->khusus_extra->ViewAttributes() ?>>
<?php echo $shift_result->khusus_extra->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->temp_id_auto->Visible) { // temp_id_auto ?>
		<td data-name="temp_id_auto"<?php echo $shift_result->temp_id_auto->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_temp_id_auto" class="shift_result_temp_id_auto">
<span<?php echo $shift_result->temp_id_auto->ViewAttributes() ?>>
<?php echo $shift_result->temp_id_auto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->jdw_kerja_m_id->Visible) { // jdw_kerja_m_id ?>
		<td data-name="jdw_kerja_m_id"<?php echo $shift_result->jdw_kerja_m_id->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_jdw_kerja_m_id" class="shift_result_jdw_kerja_m_id">
<span<?php echo $shift_result->jdw_kerja_m_id->ViewAttributes() ?>>
<?php echo $shift_result->jdw_kerja_m_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->jk_id->Visible) { // jk_id ?>
		<td data-name="jk_id"<?php echo $shift_result->jk_id->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_jk_id" class="shift_result_jk_id">
<span<?php echo $shift_result->jk_id->ViewAttributes() ?>>
<?php echo $shift_result->jk_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->jns_dok->Visible) { // jns_dok ?>
		<td data-name="jns_dok"<?php echo $shift_result->jns_dok->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_jns_dok" class="shift_result_jns_dok">
<span<?php echo $shift_result->jns_dok->ViewAttributes() ?>>
<?php echo $shift_result->jns_dok->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->izin_jenis_id->Visible) { // izin_jenis_id ?>
		<td data-name="izin_jenis_id"<?php echo $shift_result->izin_jenis_id->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_izin_jenis_id" class="shift_result_izin_jenis_id">
<span<?php echo $shift_result->izin_jenis_id->ViewAttributes() ?>>
<?php echo $shift_result->izin_jenis_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->cuti_n_id->Visible) { // cuti_n_id ?>
		<td data-name="cuti_n_id"<?php echo $shift_result->cuti_n_id->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_cuti_n_id" class="shift_result_cuti_n_id">
<span<?php echo $shift_result->cuti_n_id->ViewAttributes() ?>>
<?php echo $shift_result->cuti_n_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->libur_umum->Visible) { // libur_umum ?>
		<td data-name="libur_umum"<?php echo $shift_result->libur_umum->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_libur_umum" class="shift_result_libur_umum">
<span<?php echo $shift_result->libur_umum->ViewAttributes() ?>>
<?php echo $shift_result->libur_umum->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->libur_rutin->Visible) { // libur_rutin ?>
		<td data-name="libur_rutin"<?php echo $shift_result->libur_rutin->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_libur_rutin" class="shift_result_libur_rutin">
<span<?php echo $shift_result->libur_rutin->ViewAttributes() ?>>
<?php echo $shift_result->libur_rutin->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->jk_ot->Visible) { // jk_ot ?>
		<td data-name="jk_ot"<?php echo $shift_result->jk_ot->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_jk_ot" class="shift_result_jk_ot">
<span<?php echo $shift_result->jk_ot->ViewAttributes() ?>>
<?php echo $shift_result->jk_ot->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->scan_in->Visible) { // scan_in ?>
		<td data-name="scan_in"<?php echo $shift_result->scan_in->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_scan_in" class="shift_result_scan_in">
<span<?php echo $shift_result->scan_in->ViewAttributes() ?>>
<?php echo $shift_result->scan_in->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->att_id_in->Visible) { // att_id_in ?>
		<td data-name="att_id_in"<?php echo $shift_result->att_id_in->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_att_id_in" class="shift_result_att_id_in">
<span<?php echo $shift_result->att_id_in->ViewAttributes() ?>>
<?php echo $shift_result->att_id_in->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->late_permission->Visible) { // late_permission ?>
		<td data-name="late_permission"<?php echo $shift_result->late_permission->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_late_permission" class="shift_result_late_permission">
<span<?php echo $shift_result->late_permission->ViewAttributes() ?>>
<?php echo $shift_result->late_permission->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->late_minute->Visible) { // late_minute ?>
		<td data-name="late_minute"<?php echo $shift_result->late_minute->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_late_minute" class="shift_result_late_minute">
<span<?php echo $shift_result->late_minute->ViewAttributes() ?>>
<?php echo $shift_result->late_minute->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->late->Visible) { // late ?>
		<td data-name="late"<?php echo $shift_result->late->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_late" class="shift_result_late">
<span<?php echo $shift_result->late->ViewAttributes() ?>>
<?php echo $shift_result->late->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->break_out->Visible) { // break_out ?>
		<td data-name="break_out"<?php echo $shift_result->break_out->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_break_out" class="shift_result_break_out">
<span<?php echo $shift_result->break_out->ViewAttributes() ?>>
<?php echo $shift_result->break_out->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->att_id_break1->Visible) { // att_id_break1 ?>
		<td data-name="att_id_break1"<?php echo $shift_result->att_id_break1->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_att_id_break1" class="shift_result_att_id_break1">
<span<?php echo $shift_result->att_id_break1->ViewAttributes() ?>>
<?php echo $shift_result->att_id_break1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->break_in->Visible) { // break_in ?>
		<td data-name="break_in"<?php echo $shift_result->break_in->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_break_in" class="shift_result_break_in">
<span<?php echo $shift_result->break_in->ViewAttributes() ?>>
<?php echo $shift_result->break_in->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->att_id_break2->Visible) { // att_id_break2 ?>
		<td data-name="att_id_break2"<?php echo $shift_result->att_id_break2->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_att_id_break2" class="shift_result_att_id_break2">
<span<?php echo $shift_result->att_id_break2->ViewAttributes() ?>>
<?php echo $shift_result->att_id_break2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->break_minute->Visible) { // break_minute ?>
		<td data-name="break_minute"<?php echo $shift_result->break_minute->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_break_minute" class="shift_result_break_minute">
<span<?php echo $shift_result->break_minute->ViewAttributes() ?>>
<?php echo $shift_result->break_minute->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->break->Visible) { // break ?>
		<td data-name="break"<?php echo $shift_result->break->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_break" class="shift_result_break">
<span<?php echo $shift_result->break->ViewAttributes() ?>>
<?php echo $shift_result->break->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->break_ot_minute->Visible) { // break_ot_minute ?>
		<td data-name="break_ot_minute"<?php echo $shift_result->break_ot_minute->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_break_ot_minute" class="shift_result_break_ot_minute">
<span<?php echo $shift_result->break_ot_minute->ViewAttributes() ?>>
<?php echo $shift_result->break_ot_minute->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->break_ot->Visible) { // break_ot ?>
		<td data-name="break_ot"<?php echo $shift_result->break_ot->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_break_ot" class="shift_result_break_ot">
<span<?php echo $shift_result->break_ot->ViewAttributes() ?>>
<?php echo $shift_result->break_ot->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->early_permission->Visible) { // early_permission ?>
		<td data-name="early_permission"<?php echo $shift_result->early_permission->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_early_permission" class="shift_result_early_permission">
<span<?php echo $shift_result->early_permission->ViewAttributes() ?>>
<?php echo $shift_result->early_permission->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->early_minute->Visible) { // early_minute ?>
		<td data-name="early_minute"<?php echo $shift_result->early_minute->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_early_minute" class="shift_result_early_minute">
<span<?php echo $shift_result->early_minute->ViewAttributes() ?>>
<?php echo $shift_result->early_minute->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->early->Visible) { // early ?>
		<td data-name="early"<?php echo $shift_result->early->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_early" class="shift_result_early">
<span<?php echo $shift_result->early->ViewAttributes() ?>>
<?php echo $shift_result->early->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->scan_out->Visible) { // scan_out ?>
		<td data-name="scan_out"<?php echo $shift_result->scan_out->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_scan_out" class="shift_result_scan_out">
<span<?php echo $shift_result->scan_out->ViewAttributes() ?>>
<?php echo $shift_result->scan_out->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->att_id_out->Visible) { // att_id_out ?>
		<td data-name="att_id_out"<?php echo $shift_result->att_id_out->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_att_id_out" class="shift_result_att_id_out">
<span<?php echo $shift_result->att_id_out->ViewAttributes() ?>>
<?php echo $shift_result->att_id_out->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->durasi_minute->Visible) { // durasi_minute ?>
		<td data-name="durasi_minute"<?php echo $shift_result->durasi_minute->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_durasi_minute" class="shift_result_durasi_minute">
<span<?php echo $shift_result->durasi_minute->ViewAttributes() ?>>
<?php echo $shift_result->durasi_minute->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->durasi->Visible) { // durasi ?>
		<td data-name="durasi"<?php echo $shift_result->durasi->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_durasi" class="shift_result_durasi">
<span<?php echo $shift_result->durasi->ViewAttributes() ?>>
<?php echo $shift_result->durasi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->durasi_eot_minute->Visible) { // durasi_eot_minute ?>
		<td data-name="durasi_eot_minute"<?php echo $shift_result->durasi_eot_minute->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_durasi_eot_minute" class="shift_result_durasi_eot_minute">
<span<?php echo $shift_result->durasi_eot_minute->ViewAttributes() ?>>
<?php echo $shift_result->durasi_eot_minute->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->jk_count_as->Visible) { // jk_count_as ?>
		<td data-name="jk_count_as"<?php echo $shift_result->jk_count_as->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_jk_count_as" class="shift_result_jk_count_as">
<span<?php echo $shift_result->jk_count_as->ViewAttributes() ?>>
<?php echo $shift_result->jk_count_as->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($shift_result->status_jk->Visible) { // status_jk ?>
		<td data-name="status_jk"<?php echo $shift_result->status_jk->CellAttributes() ?>>
<span id="el<?php echo $shift_result_list->RowCnt ?>_shift_result_status_jk" class="shift_result_status_jk">
<span<?php echo $shift_result->status_jk->ViewAttributes() ?>>
<?php echo $shift_result->status_jk->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$shift_result_list->ListOptions->Render("body", "right", $shift_result_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($shift_result->CurrentAction <> "gridadd")
		$shift_result_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($shift_result->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($shift_result_list->Recordset)
	$shift_result_list->Recordset->Close();
?>
<?php if ($shift_result->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($shift_result->CurrentAction <> "gridadd" && $shift_result->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($shift_result_list->Pager)) $shift_result_list->Pager = new cPrevNextPager($shift_result_list->StartRec, $shift_result_list->DisplayRecs, $shift_result_list->TotalRecs) ?>
<?php if ($shift_result_list->Pager->RecordCount > 0 && $shift_result_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($shift_result_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $shift_result_list->PageUrl() ?>start=<?php echo $shift_result_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($shift_result_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $shift_result_list->PageUrl() ?>start=<?php echo $shift_result_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $shift_result_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($shift_result_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $shift_result_list->PageUrl() ?>start=<?php echo $shift_result_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($shift_result_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $shift_result_list->PageUrl() ?>start=<?php echo $shift_result_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $shift_result_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $shift_result_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $shift_result_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $shift_result_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($shift_result_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($shift_result_list->TotalRecs == 0 && $shift_result->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($shift_result_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($shift_result->Export == "") { ?>
<script type="text/javascript">
fshift_resultlistsrch.FilterList = <?php echo $shift_result_list->GetFilterList() ?>;
fshift_resultlistsrch.Init();
fshift_resultlist.Init();
</script>
<?php } ?>
<?php
$shift_result_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($shift_result->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$shift_result_list->Page_Terminate();
?>
