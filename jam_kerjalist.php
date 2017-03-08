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

$jam_kerja_list = NULL; // Initialize page object first

class cjam_kerja_list extends cjam_kerja {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'jam_kerja';

	// Page object name
	var $PageObjName = 'jam_kerja_list';

	// Grid form hidden field names
	var $FormName = 'fjam_kerjalist';
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

		// Table object (jam_kerja)
		if (!isset($GLOBALS["jam_kerja"]) || get_class($GLOBALS["jam_kerja"]) == "cjam_kerja") {
			$GLOBALS["jam_kerja"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jam_kerja"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "jam_kerjaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "jam_kerjadelete.php";
		$this->MultiUpdateUrl = "jam_kerjaupdate.php";

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fjam_kerjalistsrch";

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
		if (count($arrKeyFlds) >= 1) {
			$this->jk_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->jk_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fjam_kerjalistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->jk_id->AdvancedSearch->ToJSON(), ","); // Field jk_id
		$sFilterList = ew_Concat($sFilterList, $this->jk_name->AdvancedSearch->ToJSON(), ","); // Field jk_name
		$sFilterList = ew_Concat($sFilterList, $this->jk_kode->AdvancedSearch->ToJSON(), ","); // Field jk_kode
		$sFilterList = ew_Concat($sFilterList, $this->use_set->AdvancedSearch->ToJSON(), ","); // Field use_set
		$sFilterList = ew_Concat($sFilterList, $this->jk_bcin->AdvancedSearch->ToJSON(), ","); // Field jk_bcin
		$sFilterList = ew_Concat($sFilterList, $this->jk_cin->AdvancedSearch->ToJSON(), ","); // Field jk_cin
		$sFilterList = ew_Concat($sFilterList, $this->jk_ecin->AdvancedSearch->ToJSON(), ","); // Field jk_ecin
		$sFilterList = ew_Concat($sFilterList, $this->jk_tol_late->AdvancedSearch->ToJSON(), ","); // Field jk_tol_late
		$sFilterList = ew_Concat($sFilterList, $this->jk_use_ist->AdvancedSearch->ToJSON(), ","); // Field jk_use_ist
		$sFilterList = ew_Concat($sFilterList, $this->jk_ist1->AdvancedSearch->ToJSON(), ","); // Field jk_ist1
		$sFilterList = ew_Concat($sFilterList, $this->jk_ist2->AdvancedSearch->ToJSON(), ","); // Field jk_ist2
		$sFilterList = ew_Concat($sFilterList, $this->jk_tol_early->AdvancedSearch->ToJSON(), ","); // Field jk_tol_early
		$sFilterList = ew_Concat($sFilterList, $this->jk_bcout->AdvancedSearch->ToJSON(), ","); // Field jk_bcout
		$sFilterList = ew_Concat($sFilterList, $this->jk_cout->AdvancedSearch->ToJSON(), ","); // Field jk_cout
		$sFilterList = ew_Concat($sFilterList, $this->jk_ecout->AdvancedSearch->ToJSON(), ","); // Field jk_ecout
		$sFilterList = ew_Concat($sFilterList, $this->use_eot->AdvancedSearch->ToJSON(), ","); // Field use_eot
		$sFilterList = ew_Concat($sFilterList, $this->min_eot->AdvancedSearch->ToJSON(), ","); // Field min_eot
		$sFilterList = ew_Concat($sFilterList, $this->max_eot->AdvancedSearch->ToJSON(), ","); // Field max_eot
		$sFilterList = ew_Concat($sFilterList, $this->reduce_eot->AdvancedSearch->ToJSON(), ","); // Field reduce_eot
		$sFilterList = ew_Concat($sFilterList, $this->jk_durasi->AdvancedSearch->ToJSON(), ","); // Field jk_durasi
		$sFilterList = ew_Concat($sFilterList, $this->jk_countas->AdvancedSearch->ToJSON(), ","); // Field jk_countas
		$sFilterList = ew_Concat($sFilterList, $this->jk_min_countas->AdvancedSearch->ToJSON(), ","); // Field jk_min_countas
		$sFilterList = ew_Concat($sFilterList, $this->jk_ket->AdvancedSearch->ToJSON(), ","); // Field jk_ket
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fjam_kerjalistsrch", $filters);

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

		// Field jk_id
		$this->jk_id->AdvancedSearch->SearchValue = @$filter["x_jk_id"];
		$this->jk_id->AdvancedSearch->SearchOperator = @$filter["z_jk_id"];
		$this->jk_id->AdvancedSearch->SearchCondition = @$filter["v_jk_id"];
		$this->jk_id->AdvancedSearch->SearchValue2 = @$filter["y_jk_id"];
		$this->jk_id->AdvancedSearch->SearchOperator2 = @$filter["w_jk_id"];
		$this->jk_id->AdvancedSearch->Save();

		// Field jk_name
		$this->jk_name->AdvancedSearch->SearchValue = @$filter["x_jk_name"];
		$this->jk_name->AdvancedSearch->SearchOperator = @$filter["z_jk_name"];
		$this->jk_name->AdvancedSearch->SearchCondition = @$filter["v_jk_name"];
		$this->jk_name->AdvancedSearch->SearchValue2 = @$filter["y_jk_name"];
		$this->jk_name->AdvancedSearch->SearchOperator2 = @$filter["w_jk_name"];
		$this->jk_name->AdvancedSearch->Save();

		// Field jk_kode
		$this->jk_kode->AdvancedSearch->SearchValue = @$filter["x_jk_kode"];
		$this->jk_kode->AdvancedSearch->SearchOperator = @$filter["z_jk_kode"];
		$this->jk_kode->AdvancedSearch->SearchCondition = @$filter["v_jk_kode"];
		$this->jk_kode->AdvancedSearch->SearchValue2 = @$filter["y_jk_kode"];
		$this->jk_kode->AdvancedSearch->SearchOperator2 = @$filter["w_jk_kode"];
		$this->jk_kode->AdvancedSearch->Save();

		// Field use_set
		$this->use_set->AdvancedSearch->SearchValue = @$filter["x_use_set"];
		$this->use_set->AdvancedSearch->SearchOperator = @$filter["z_use_set"];
		$this->use_set->AdvancedSearch->SearchCondition = @$filter["v_use_set"];
		$this->use_set->AdvancedSearch->SearchValue2 = @$filter["y_use_set"];
		$this->use_set->AdvancedSearch->SearchOperator2 = @$filter["w_use_set"];
		$this->use_set->AdvancedSearch->Save();

		// Field jk_bcin
		$this->jk_bcin->AdvancedSearch->SearchValue = @$filter["x_jk_bcin"];
		$this->jk_bcin->AdvancedSearch->SearchOperator = @$filter["z_jk_bcin"];
		$this->jk_bcin->AdvancedSearch->SearchCondition = @$filter["v_jk_bcin"];
		$this->jk_bcin->AdvancedSearch->SearchValue2 = @$filter["y_jk_bcin"];
		$this->jk_bcin->AdvancedSearch->SearchOperator2 = @$filter["w_jk_bcin"];
		$this->jk_bcin->AdvancedSearch->Save();

		// Field jk_cin
		$this->jk_cin->AdvancedSearch->SearchValue = @$filter["x_jk_cin"];
		$this->jk_cin->AdvancedSearch->SearchOperator = @$filter["z_jk_cin"];
		$this->jk_cin->AdvancedSearch->SearchCondition = @$filter["v_jk_cin"];
		$this->jk_cin->AdvancedSearch->SearchValue2 = @$filter["y_jk_cin"];
		$this->jk_cin->AdvancedSearch->SearchOperator2 = @$filter["w_jk_cin"];
		$this->jk_cin->AdvancedSearch->Save();

		// Field jk_ecin
		$this->jk_ecin->AdvancedSearch->SearchValue = @$filter["x_jk_ecin"];
		$this->jk_ecin->AdvancedSearch->SearchOperator = @$filter["z_jk_ecin"];
		$this->jk_ecin->AdvancedSearch->SearchCondition = @$filter["v_jk_ecin"];
		$this->jk_ecin->AdvancedSearch->SearchValue2 = @$filter["y_jk_ecin"];
		$this->jk_ecin->AdvancedSearch->SearchOperator2 = @$filter["w_jk_ecin"];
		$this->jk_ecin->AdvancedSearch->Save();

		// Field jk_tol_late
		$this->jk_tol_late->AdvancedSearch->SearchValue = @$filter["x_jk_tol_late"];
		$this->jk_tol_late->AdvancedSearch->SearchOperator = @$filter["z_jk_tol_late"];
		$this->jk_tol_late->AdvancedSearch->SearchCondition = @$filter["v_jk_tol_late"];
		$this->jk_tol_late->AdvancedSearch->SearchValue2 = @$filter["y_jk_tol_late"];
		$this->jk_tol_late->AdvancedSearch->SearchOperator2 = @$filter["w_jk_tol_late"];
		$this->jk_tol_late->AdvancedSearch->Save();

		// Field jk_use_ist
		$this->jk_use_ist->AdvancedSearch->SearchValue = @$filter["x_jk_use_ist"];
		$this->jk_use_ist->AdvancedSearch->SearchOperator = @$filter["z_jk_use_ist"];
		$this->jk_use_ist->AdvancedSearch->SearchCondition = @$filter["v_jk_use_ist"];
		$this->jk_use_ist->AdvancedSearch->SearchValue2 = @$filter["y_jk_use_ist"];
		$this->jk_use_ist->AdvancedSearch->SearchOperator2 = @$filter["w_jk_use_ist"];
		$this->jk_use_ist->AdvancedSearch->Save();

		// Field jk_ist1
		$this->jk_ist1->AdvancedSearch->SearchValue = @$filter["x_jk_ist1"];
		$this->jk_ist1->AdvancedSearch->SearchOperator = @$filter["z_jk_ist1"];
		$this->jk_ist1->AdvancedSearch->SearchCondition = @$filter["v_jk_ist1"];
		$this->jk_ist1->AdvancedSearch->SearchValue2 = @$filter["y_jk_ist1"];
		$this->jk_ist1->AdvancedSearch->SearchOperator2 = @$filter["w_jk_ist1"];
		$this->jk_ist1->AdvancedSearch->Save();

		// Field jk_ist2
		$this->jk_ist2->AdvancedSearch->SearchValue = @$filter["x_jk_ist2"];
		$this->jk_ist2->AdvancedSearch->SearchOperator = @$filter["z_jk_ist2"];
		$this->jk_ist2->AdvancedSearch->SearchCondition = @$filter["v_jk_ist2"];
		$this->jk_ist2->AdvancedSearch->SearchValue2 = @$filter["y_jk_ist2"];
		$this->jk_ist2->AdvancedSearch->SearchOperator2 = @$filter["w_jk_ist2"];
		$this->jk_ist2->AdvancedSearch->Save();

		// Field jk_tol_early
		$this->jk_tol_early->AdvancedSearch->SearchValue = @$filter["x_jk_tol_early"];
		$this->jk_tol_early->AdvancedSearch->SearchOperator = @$filter["z_jk_tol_early"];
		$this->jk_tol_early->AdvancedSearch->SearchCondition = @$filter["v_jk_tol_early"];
		$this->jk_tol_early->AdvancedSearch->SearchValue2 = @$filter["y_jk_tol_early"];
		$this->jk_tol_early->AdvancedSearch->SearchOperator2 = @$filter["w_jk_tol_early"];
		$this->jk_tol_early->AdvancedSearch->Save();

		// Field jk_bcout
		$this->jk_bcout->AdvancedSearch->SearchValue = @$filter["x_jk_bcout"];
		$this->jk_bcout->AdvancedSearch->SearchOperator = @$filter["z_jk_bcout"];
		$this->jk_bcout->AdvancedSearch->SearchCondition = @$filter["v_jk_bcout"];
		$this->jk_bcout->AdvancedSearch->SearchValue2 = @$filter["y_jk_bcout"];
		$this->jk_bcout->AdvancedSearch->SearchOperator2 = @$filter["w_jk_bcout"];
		$this->jk_bcout->AdvancedSearch->Save();

		// Field jk_cout
		$this->jk_cout->AdvancedSearch->SearchValue = @$filter["x_jk_cout"];
		$this->jk_cout->AdvancedSearch->SearchOperator = @$filter["z_jk_cout"];
		$this->jk_cout->AdvancedSearch->SearchCondition = @$filter["v_jk_cout"];
		$this->jk_cout->AdvancedSearch->SearchValue2 = @$filter["y_jk_cout"];
		$this->jk_cout->AdvancedSearch->SearchOperator2 = @$filter["w_jk_cout"];
		$this->jk_cout->AdvancedSearch->Save();

		// Field jk_ecout
		$this->jk_ecout->AdvancedSearch->SearchValue = @$filter["x_jk_ecout"];
		$this->jk_ecout->AdvancedSearch->SearchOperator = @$filter["z_jk_ecout"];
		$this->jk_ecout->AdvancedSearch->SearchCondition = @$filter["v_jk_ecout"];
		$this->jk_ecout->AdvancedSearch->SearchValue2 = @$filter["y_jk_ecout"];
		$this->jk_ecout->AdvancedSearch->SearchOperator2 = @$filter["w_jk_ecout"];
		$this->jk_ecout->AdvancedSearch->Save();

		// Field use_eot
		$this->use_eot->AdvancedSearch->SearchValue = @$filter["x_use_eot"];
		$this->use_eot->AdvancedSearch->SearchOperator = @$filter["z_use_eot"];
		$this->use_eot->AdvancedSearch->SearchCondition = @$filter["v_use_eot"];
		$this->use_eot->AdvancedSearch->SearchValue2 = @$filter["y_use_eot"];
		$this->use_eot->AdvancedSearch->SearchOperator2 = @$filter["w_use_eot"];
		$this->use_eot->AdvancedSearch->Save();

		// Field min_eot
		$this->min_eot->AdvancedSearch->SearchValue = @$filter["x_min_eot"];
		$this->min_eot->AdvancedSearch->SearchOperator = @$filter["z_min_eot"];
		$this->min_eot->AdvancedSearch->SearchCondition = @$filter["v_min_eot"];
		$this->min_eot->AdvancedSearch->SearchValue2 = @$filter["y_min_eot"];
		$this->min_eot->AdvancedSearch->SearchOperator2 = @$filter["w_min_eot"];
		$this->min_eot->AdvancedSearch->Save();

		// Field max_eot
		$this->max_eot->AdvancedSearch->SearchValue = @$filter["x_max_eot"];
		$this->max_eot->AdvancedSearch->SearchOperator = @$filter["z_max_eot"];
		$this->max_eot->AdvancedSearch->SearchCondition = @$filter["v_max_eot"];
		$this->max_eot->AdvancedSearch->SearchValue2 = @$filter["y_max_eot"];
		$this->max_eot->AdvancedSearch->SearchOperator2 = @$filter["w_max_eot"];
		$this->max_eot->AdvancedSearch->Save();

		// Field reduce_eot
		$this->reduce_eot->AdvancedSearch->SearchValue = @$filter["x_reduce_eot"];
		$this->reduce_eot->AdvancedSearch->SearchOperator = @$filter["z_reduce_eot"];
		$this->reduce_eot->AdvancedSearch->SearchCondition = @$filter["v_reduce_eot"];
		$this->reduce_eot->AdvancedSearch->SearchValue2 = @$filter["y_reduce_eot"];
		$this->reduce_eot->AdvancedSearch->SearchOperator2 = @$filter["w_reduce_eot"];
		$this->reduce_eot->AdvancedSearch->Save();

		// Field jk_durasi
		$this->jk_durasi->AdvancedSearch->SearchValue = @$filter["x_jk_durasi"];
		$this->jk_durasi->AdvancedSearch->SearchOperator = @$filter["z_jk_durasi"];
		$this->jk_durasi->AdvancedSearch->SearchCondition = @$filter["v_jk_durasi"];
		$this->jk_durasi->AdvancedSearch->SearchValue2 = @$filter["y_jk_durasi"];
		$this->jk_durasi->AdvancedSearch->SearchOperator2 = @$filter["w_jk_durasi"];
		$this->jk_durasi->AdvancedSearch->Save();

		// Field jk_countas
		$this->jk_countas->AdvancedSearch->SearchValue = @$filter["x_jk_countas"];
		$this->jk_countas->AdvancedSearch->SearchOperator = @$filter["z_jk_countas"];
		$this->jk_countas->AdvancedSearch->SearchCondition = @$filter["v_jk_countas"];
		$this->jk_countas->AdvancedSearch->SearchValue2 = @$filter["y_jk_countas"];
		$this->jk_countas->AdvancedSearch->SearchOperator2 = @$filter["w_jk_countas"];
		$this->jk_countas->AdvancedSearch->Save();

		// Field jk_min_countas
		$this->jk_min_countas->AdvancedSearch->SearchValue = @$filter["x_jk_min_countas"];
		$this->jk_min_countas->AdvancedSearch->SearchOperator = @$filter["z_jk_min_countas"];
		$this->jk_min_countas->AdvancedSearch->SearchCondition = @$filter["v_jk_min_countas"];
		$this->jk_min_countas->AdvancedSearch->SearchValue2 = @$filter["y_jk_min_countas"];
		$this->jk_min_countas->AdvancedSearch->SearchOperator2 = @$filter["w_jk_min_countas"];
		$this->jk_min_countas->AdvancedSearch->Save();

		// Field jk_ket
		$this->jk_ket->AdvancedSearch->SearchValue = @$filter["x_jk_ket"];
		$this->jk_ket->AdvancedSearch->SearchOperator = @$filter["z_jk_ket"];
		$this->jk_ket->AdvancedSearch->SearchCondition = @$filter["v_jk_ket"];
		$this->jk_ket->AdvancedSearch->SearchValue2 = @$filter["y_jk_ket"];
		$this->jk_ket->AdvancedSearch->SearchOperator2 = @$filter["w_jk_ket"];
		$this->jk_ket->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->jk_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->jk_kode, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->jk_ket, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
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
			$this->UpdateSort($this->jk_id, $bCtrl); // jk_id
			$this->UpdateSort($this->jk_name, $bCtrl); // jk_name
			$this->UpdateSort($this->jk_kode, $bCtrl); // jk_kode
			$this->UpdateSort($this->use_set, $bCtrl); // use_set
			$this->UpdateSort($this->jk_bcin, $bCtrl); // jk_bcin
			$this->UpdateSort($this->jk_cin, $bCtrl); // jk_cin
			$this->UpdateSort($this->jk_ecin, $bCtrl); // jk_ecin
			$this->UpdateSort($this->jk_tol_late, $bCtrl); // jk_tol_late
			$this->UpdateSort($this->jk_use_ist, $bCtrl); // jk_use_ist
			$this->UpdateSort($this->jk_ist1, $bCtrl); // jk_ist1
			$this->UpdateSort($this->jk_ist2, $bCtrl); // jk_ist2
			$this->UpdateSort($this->jk_tol_early, $bCtrl); // jk_tol_early
			$this->UpdateSort($this->jk_bcout, $bCtrl); // jk_bcout
			$this->UpdateSort($this->jk_cout, $bCtrl); // jk_cout
			$this->UpdateSort($this->jk_ecout, $bCtrl); // jk_ecout
			$this->UpdateSort($this->use_eot, $bCtrl); // use_eot
			$this->UpdateSort($this->min_eot, $bCtrl); // min_eot
			$this->UpdateSort($this->max_eot, $bCtrl); // max_eot
			$this->UpdateSort($this->reduce_eot, $bCtrl); // reduce_eot
			$this->UpdateSort($this->jk_durasi, $bCtrl); // jk_durasi
			$this->UpdateSort($this->jk_countas, $bCtrl); // jk_countas
			$this->UpdateSort($this->jk_min_countas, $bCtrl); // jk_min_countas
			$this->UpdateSort($this->jk_ket, $bCtrl); // jk_ket
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
				$this->jk_id->setSort("");
				$this->jk_name->setSort("");
				$this->jk_kode->setSort("");
				$this->use_set->setSort("");
				$this->jk_bcin->setSort("");
				$this->jk_cin->setSort("");
				$this->jk_ecin->setSort("");
				$this->jk_tol_late->setSort("");
				$this->jk_use_ist->setSort("");
				$this->jk_ist1->setSort("");
				$this->jk_ist2->setSort("");
				$this->jk_tol_early->setSort("");
				$this->jk_bcout->setSort("");
				$this->jk_cout->setSort("");
				$this->jk_ecout->setSort("");
				$this->use_eot->setSort("");
				$this->min_eot->setSort("");
				$this->max_eot->setSort("");
				$this->reduce_eot->setSort("");
				$this->jk_durasi->setSort("");
				$this->jk_countas->setSort("");
				$this->jk_min_countas->setSort("");
				$this->jk_ket->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->jk_id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fjam_kerjalistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fjam_kerjalistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fjam_kerjalist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fjam_kerjalistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("jk_id")) <> "")
			$this->jk_id->CurrentValue = $this->getKey("jk_id"); // jk_id
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
		$item->Body = "<button id=\"emf_jam_kerja\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_jam_kerja',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fjam_kerjalist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($jam_kerja_list)) $jam_kerja_list = new cjam_kerja_list();

// Page init
$jam_kerja_list->Page_Init();

// Page main
$jam_kerja_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jam_kerja_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($jam_kerja->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fjam_kerjalist = new ew_Form("fjam_kerjalist", "list");
fjam_kerjalist.FormKeyCountName = '<?php echo $jam_kerja_list->FormKeyCountName ?>';

// Form_CustomValidate event
fjam_kerjalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjam_kerjalist.ValidateRequired = true;
<?php } else { ?>
fjam_kerjalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fjam_kerjalistsrch = new ew_Form("fjam_kerjalistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($jam_kerja->Export == "") { ?>
<div class="ewToolbar">
<?php if ($jam_kerja->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($jam_kerja_list->TotalRecs > 0 && $jam_kerja_list->ExportOptions->Visible()) { ?>
<?php $jam_kerja_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($jam_kerja_list->SearchOptions->Visible()) { ?>
<?php $jam_kerja_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($jam_kerja_list->FilterOptions->Visible()) { ?>
<?php $jam_kerja_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($jam_kerja->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $jam_kerja_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($jam_kerja_list->TotalRecs <= 0)
			$jam_kerja_list->TotalRecs = $jam_kerja->SelectRecordCount();
	} else {
		if (!$jam_kerja_list->Recordset && ($jam_kerja_list->Recordset = $jam_kerja_list->LoadRecordset()))
			$jam_kerja_list->TotalRecs = $jam_kerja_list->Recordset->RecordCount();
	}
	$jam_kerja_list->StartRec = 1;
	if ($jam_kerja_list->DisplayRecs <= 0 || ($jam_kerja->Export <> "" && $jam_kerja->ExportAll)) // Display all records
		$jam_kerja_list->DisplayRecs = $jam_kerja_list->TotalRecs;
	if (!($jam_kerja->Export <> "" && $jam_kerja->ExportAll))
		$jam_kerja_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$jam_kerja_list->Recordset = $jam_kerja_list->LoadRecordset($jam_kerja_list->StartRec-1, $jam_kerja_list->DisplayRecs);

	// Set no record found message
	if ($jam_kerja->CurrentAction == "" && $jam_kerja_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$jam_kerja_list->setWarningMessage(ew_DeniedMsg());
		if ($jam_kerja_list->SearchWhere == "0=101")
			$jam_kerja_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$jam_kerja_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$jam_kerja_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($jam_kerja->Export == "" && $jam_kerja->CurrentAction == "") { ?>
<form name="fjam_kerjalistsrch" id="fjam_kerjalistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($jam_kerja_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fjam_kerjalistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="jam_kerja">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($jam_kerja_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($jam_kerja_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $jam_kerja_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($jam_kerja_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($jam_kerja_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($jam_kerja_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($jam_kerja_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $jam_kerja_list->ShowPageHeader(); ?>
<?php
$jam_kerja_list->ShowMessage();
?>
<?php if ($jam_kerja_list->TotalRecs > 0 || $jam_kerja->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid jam_kerja">
<form name="fjam_kerjalist" id="fjam_kerjalist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jam_kerja_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jam_kerja_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jam_kerja">
<div id="gmp_jam_kerja" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($jam_kerja_list->TotalRecs > 0 || $jam_kerja->CurrentAction == "gridedit") { ?>
<table id="tbl_jam_kerjalist" class="table ewTable">
<?php echo $jam_kerja->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$jam_kerja_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$jam_kerja_list->RenderListOptions();

// Render list options (header, left)
$jam_kerja_list->ListOptions->Render("header", "left");
?>
<?php if ($jam_kerja->jk_id->Visible) { // jk_id ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_id) == "") { ?>
		<th data-name="jk_id"><div id="elh_jam_kerja_jk_id" class="jam_kerja_jk_id"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_id) ?>',2);"><div id="elh_jam_kerja_jk_id" class="jam_kerja_jk_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_name->Visible) { // jk_name ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_name) == "") { ?>
		<th data-name="jk_name"><div id="elh_jam_kerja_jk_name" class="jam_kerja_jk_name"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_name) ?>',2);"><div id="elh_jam_kerja_jk_name" class="jam_kerja_jk_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_kode->Visible) { // jk_kode ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_kode) == "") { ?>
		<th data-name="jk_kode"><div id="elh_jam_kerja_jk_kode" class="jam_kerja_jk_kode"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_kode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_kode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_kode) ?>',2);"><div id="elh_jam_kerja_jk_kode" class="jam_kerja_jk_kode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_kode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_kode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_kode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->use_set->Visible) { // use_set ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->use_set) == "") { ?>
		<th data-name="use_set"><div id="elh_jam_kerja_use_set" class="jam_kerja_use_set"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->use_set->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="use_set"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->use_set) ?>',2);"><div id="elh_jam_kerja_use_set" class="jam_kerja_use_set">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->use_set->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->use_set->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->use_set->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_bcin->Visible) { // jk_bcin ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_bcin) == "") { ?>
		<th data-name="jk_bcin"><div id="elh_jam_kerja_jk_bcin" class="jam_kerja_jk_bcin"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_bcin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_bcin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_bcin) ?>',2);"><div id="elh_jam_kerja_jk_bcin" class="jam_kerja_jk_bcin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_bcin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_bcin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_bcin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_cin->Visible) { // jk_cin ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_cin) == "") { ?>
		<th data-name="jk_cin"><div id="elh_jam_kerja_jk_cin" class="jam_kerja_jk_cin"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_cin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_cin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_cin) ?>',2);"><div id="elh_jam_kerja_jk_cin" class="jam_kerja_jk_cin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_cin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_cin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_cin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_ecin->Visible) { // jk_ecin ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_ecin) == "") { ?>
		<th data-name="jk_ecin"><div id="elh_jam_kerja_jk_ecin" class="jam_kerja_jk_ecin"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_ecin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_ecin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_ecin) ?>',2);"><div id="elh_jam_kerja_jk_ecin" class="jam_kerja_jk_ecin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_ecin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_ecin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_ecin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_tol_late->Visible) { // jk_tol_late ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_tol_late) == "") { ?>
		<th data-name="jk_tol_late"><div id="elh_jam_kerja_jk_tol_late" class="jam_kerja_jk_tol_late"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_tol_late->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_tol_late"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_tol_late) ?>',2);"><div id="elh_jam_kerja_jk_tol_late" class="jam_kerja_jk_tol_late">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_tol_late->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_tol_late->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_tol_late->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_use_ist->Visible) { // jk_use_ist ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_use_ist) == "") { ?>
		<th data-name="jk_use_ist"><div id="elh_jam_kerja_jk_use_ist" class="jam_kerja_jk_use_ist"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_use_ist->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_use_ist"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_use_ist) ?>',2);"><div id="elh_jam_kerja_jk_use_ist" class="jam_kerja_jk_use_ist">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_use_ist->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_use_ist->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_use_ist->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_ist1->Visible) { // jk_ist1 ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_ist1) == "") { ?>
		<th data-name="jk_ist1"><div id="elh_jam_kerja_jk_ist1" class="jam_kerja_jk_ist1"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_ist1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_ist1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_ist1) ?>',2);"><div id="elh_jam_kerja_jk_ist1" class="jam_kerja_jk_ist1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_ist1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_ist1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_ist1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_ist2->Visible) { // jk_ist2 ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_ist2) == "") { ?>
		<th data-name="jk_ist2"><div id="elh_jam_kerja_jk_ist2" class="jam_kerja_jk_ist2"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_ist2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_ist2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_ist2) ?>',2);"><div id="elh_jam_kerja_jk_ist2" class="jam_kerja_jk_ist2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_ist2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_ist2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_ist2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_tol_early->Visible) { // jk_tol_early ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_tol_early) == "") { ?>
		<th data-name="jk_tol_early"><div id="elh_jam_kerja_jk_tol_early" class="jam_kerja_jk_tol_early"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_tol_early->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_tol_early"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_tol_early) ?>',2);"><div id="elh_jam_kerja_jk_tol_early" class="jam_kerja_jk_tol_early">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_tol_early->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_tol_early->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_tol_early->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_bcout->Visible) { // jk_bcout ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_bcout) == "") { ?>
		<th data-name="jk_bcout"><div id="elh_jam_kerja_jk_bcout" class="jam_kerja_jk_bcout"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_bcout->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_bcout"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_bcout) ?>',2);"><div id="elh_jam_kerja_jk_bcout" class="jam_kerja_jk_bcout">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_bcout->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_bcout->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_bcout->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_cout->Visible) { // jk_cout ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_cout) == "") { ?>
		<th data-name="jk_cout"><div id="elh_jam_kerja_jk_cout" class="jam_kerja_jk_cout"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_cout->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_cout"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_cout) ?>',2);"><div id="elh_jam_kerja_jk_cout" class="jam_kerja_jk_cout">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_cout->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_cout->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_cout->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_ecout->Visible) { // jk_ecout ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_ecout) == "") { ?>
		<th data-name="jk_ecout"><div id="elh_jam_kerja_jk_ecout" class="jam_kerja_jk_ecout"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_ecout->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_ecout"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_ecout) ?>',2);"><div id="elh_jam_kerja_jk_ecout" class="jam_kerja_jk_ecout">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_ecout->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_ecout->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_ecout->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->use_eot->Visible) { // use_eot ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->use_eot) == "") { ?>
		<th data-name="use_eot"><div id="elh_jam_kerja_use_eot" class="jam_kerja_use_eot"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->use_eot->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="use_eot"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->use_eot) ?>',2);"><div id="elh_jam_kerja_use_eot" class="jam_kerja_use_eot">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->use_eot->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->use_eot->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->use_eot->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->min_eot->Visible) { // min_eot ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->min_eot) == "") { ?>
		<th data-name="min_eot"><div id="elh_jam_kerja_min_eot" class="jam_kerja_min_eot"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->min_eot->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="min_eot"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->min_eot) ?>',2);"><div id="elh_jam_kerja_min_eot" class="jam_kerja_min_eot">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->min_eot->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->min_eot->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->min_eot->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->max_eot->Visible) { // max_eot ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->max_eot) == "") { ?>
		<th data-name="max_eot"><div id="elh_jam_kerja_max_eot" class="jam_kerja_max_eot"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->max_eot->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="max_eot"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->max_eot) ?>',2);"><div id="elh_jam_kerja_max_eot" class="jam_kerja_max_eot">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->max_eot->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->max_eot->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->max_eot->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->reduce_eot->Visible) { // reduce_eot ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->reduce_eot) == "") { ?>
		<th data-name="reduce_eot"><div id="elh_jam_kerja_reduce_eot" class="jam_kerja_reduce_eot"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->reduce_eot->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="reduce_eot"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->reduce_eot) ?>',2);"><div id="elh_jam_kerja_reduce_eot" class="jam_kerja_reduce_eot">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->reduce_eot->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->reduce_eot->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->reduce_eot->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_durasi->Visible) { // jk_durasi ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_durasi) == "") { ?>
		<th data-name="jk_durasi"><div id="elh_jam_kerja_jk_durasi" class="jam_kerja_jk_durasi"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_durasi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_durasi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_durasi) ?>',2);"><div id="elh_jam_kerja_jk_durasi" class="jam_kerja_jk_durasi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_durasi->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_durasi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_durasi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_countas->Visible) { // jk_countas ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_countas) == "") { ?>
		<th data-name="jk_countas"><div id="elh_jam_kerja_jk_countas" class="jam_kerja_jk_countas"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_countas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_countas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_countas) ?>',2);"><div id="elh_jam_kerja_jk_countas" class="jam_kerja_jk_countas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_countas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_countas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_countas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_min_countas->Visible) { // jk_min_countas ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_min_countas) == "") { ?>
		<th data-name="jk_min_countas"><div id="elh_jam_kerja_jk_min_countas" class="jam_kerja_jk_min_countas"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_min_countas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_min_countas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_min_countas) ?>',2);"><div id="elh_jam_kerja_jk_min_countas" class="jam_kerja_jk_min_countas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_min_countas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_min_countas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_min_countas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jam_kerja->jk_ket->Visible) { // jk_ket ?>
	<?php if ($jam_kerja->SortUrl($jam_kerja->jk_ket) == "") { ?>
		<th data-name="jk_ket"><div id="elh_jam_kerja_jk_ket" class="jam_kerja_jk_ket"><div class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_ket->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jk_ket"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $jam_kerja->SortUrl($jam_kerja->jk_ket) ?>',2);"><div id="elh_jam_kerja_jk_ket" class="jam_kerja_jk_ket">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jam_kerja->jk_ket->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($jam_kerja->jk_ket->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jam_kerja->jk_ket->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$jam_kerja_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($jam_kerja->ExportAll && $jam_kerja->Export <> "") {
	$jam_kerja_list->StopRec = $jam_kerja_list->TotalRecs;
} else {

	// Set the last record to display
	if ($jam_kerja_list->TotalRecs > $jam_kerja_list->StartRec + $jam_kerja_list->DisplayRecs - 1)
		$jam_kerja_list->StopRec = $jam_kerja_list->StartRec + $jam_kerja_list->DisplayRecs - 1;
	else
		$jam_kerja_list->StopRec = $jam_kerja_list->TotalRecs;
}
$jam_kerja_list->RecCnt = $jam_kerja_list->StartRec - 1;
if ($jam_kerja_list->Recordset && !$jam_kerja_list->Recordset->EOF) {
	$jam_kerja_list->Recordset->MoveFirst();
	$bSelectLimit = $jam_kerja_list->UseSelectLimit;
	if (!$bSelectLimit && $jam_kerja_list->StartRec > 1)
		$jam_kerja_list->Recordset->Move($jam_kerja_list->StartRec - 1);
} elseif (!$jam_kerja->AllowAddDeleteRow && $jam_kerja_list->StopRec == 0) {
	$jam_kerja_list->StopRec = $jam_kerja->GridAddRowCount;
}

// Initialize aggregate
$jam_kerja->RowType = EW_ROWTYPE_AGGREGATEINIT;
$jam_kerja->ResetAttrs();
$jam_kerja_list->RenderRow();
while ($jam_kerja_list->RecCnt < $jam_kerja_list->StopRec) {
	$jam_kerja_list->RecCnt++;
	if (intval($jam_kerja_list->RecCnt) >= intval($jam_kerja_list->StartRec)) {
		$jam_kerja_list->RowCnt++;

		// Set up key count
		$jam_kerja_list->KeyCount = $jam_kerja_list->RowIndex;

		// Init row class and style
		$jam_kerja->ResetAttrs();
		$jam_kerja->CssClass = "";
		if ($jam_kerja->CurrentAction == "gridadd") {
		} else {
			$jam_kerja_list->LoadRowValues($jam_kerja_list->Recordset); // Load row values
		}
		$jam_kerja->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$jam_kerja->RowAttrs = array_merge($jam_kerja->RowAttrs, array('data-rowindex'=>$jam_kerja_list->RowCnt, 'id'=>'r' . $jam_kerja_list->RowCnt . '_jam_kerja', 'data-rowtype'=>$jam_kerja->RowType));

		// Render row
		$jam_kerja_list->RenderRow();

		// Render list options
		$jam_kerja_list->RenderListOptions();
?>
	<tr<?php echo $jam_kerja->RowAttributes() ?>>
<?php

// Render list options (body, left)
$jam_kerja_list->ListOptions->Render("body", "left", $jam_kerja_list->RowCnt);
?>
	<?php if ($jam_kerja->jk_id->Visible) { // jk_id ?>
		<td data-name="jk_id"<?php echo $jam_kerja->jk_id->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_id" class="jam_kerja_jk_id">
<span<?php echo $jam_kerja->jk_id->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_id->ListViewValue() ?></span>
</span>
<a id="<?php echo $jam_kerja_list->PageObjName . "_row_" . $jam_kerja_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($jam_kerja->jk_name->Visible) { // jk_name ?>
		<td data-name="jk_name"<?php echo $jam_kerja->jk_name->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_name" class="jam_kerja_jk_name">
<span<?php echo $jam_kerja->jk_name->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_kode->Visible) { // jk_kode ?>
		<td data-name="jk_kode"<?php echo $jam_kerja->jk_kode->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_kode" class="jam_kerja_jk_kode">
<span<?php echo $jam_kerja->jk_kode->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_kode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->use_set->Visible) { // use_set ?>
		<td data-name="use_set"<?php echo $jam_kerja->use_set->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_use_set" class="jam_kerja_use_set">
<span<?php echo $jam_kerja->use_set->ViewAttributes() ?>>
<?php echo $jam_kerja->use_set->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_bcin->Visible) { // jk_bcin ?>
		<td data-name="jk_bcin"<?php echo $jam_kerja->jk_bcin->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_bcin" class="jam_kerja_jk_bcin">
<span<?php echo $jam_kerja->jk_bcin->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_bcin->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_cin->Visible) { // jk_cin ?>
		<td data-name="jk_cin"<?php echo $jam_kerja->jk_cin->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_cin" class="jam_kerja_jk_cin">
<span<?php echo $jam_kerja->jk_cin->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_cin->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_ecin->Visible) { // jk_ecin ?>
		<td data-name="jk_ecin"<?php echo $jam_kerja->jk_ecin->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_ecin" class="jam_kerja_jk_ecin">
<span<?php echo $jam_kerja->jk_ecin->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ecin->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_tol_late->Visible) { // jk_tol_late ?>
		<td data-name="jk_tol_late"<?php echo $jam_kerja->jk_tol_late->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_tol_late" class="jam_kerja_jk_tol_late">
<span<?php echo $jam_kerja->jk_tol_late->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_tol_late->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_use_ist->Visible) { // jk_use_ist ?>
		<td data-name="jk_use_ist"<?php echo $jam_kerja->jk_use_ist->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_use_ist" class="jam_kerja_jk_use_ist">
<span<?php echo $jam_kerja->jk_use_ist->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_use_ist->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_ist1->Visible) { // jk_ist1 ?>
		<td data-name="jk_ist1"<?php echo $jam_kerja->jk_ist1->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_ist1" class="jam_kerja_jk_ist1">
<span<?php echo $jam_kerja->jk_ist1->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ist1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_ist2->Visible) { // jk_ist2 ?>
		<td data-name="jk_ist2"<?php echo $jam_kerja->jk_ist2->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_ist2" class="jam_kerja_jk_ist2">
<span<?php echo $jam_kerja->jk_ist2->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ist2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_tol_early->Visible) { // jk_tol_early ?>
		<td data-name="jk_tol_early"<?php echo $jam_kerja->jk_tol_early->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_tol_early" class="jam_kerja_jk_tol_early">
<span<?php echo $jam_kerja->jk_tol_early->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_tol_early->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_bcout->Visible) { // jk_bcout ?>
		<td data-name="jk_bcout"<?php echo $jam_kerja->jk_bcout->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_bcout" class="jam_kerja_jk_bcout">
<span<?php echo $jam_kerja->jk_bcout->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_bcout->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_cout->Visible) { // jk_cout ?>
		<td data-name="jk_cout"<?php echo $jam_kerja->jk_cout->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_cout" class="jam_kerja_jk_cout">
<span<?php echo $jam_kerja->jk_cout->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_cout->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_ecout->Visible) { // jk_ecout ?>
		<td data-name="jk_ecout"<?php echo $jam_kerja->jk_ecout->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_ecout" class="jam_kerja_jk_ecout">
<span<?php echo $jam_kerja->jk_ecout->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ecout->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->use_eot->Visible) { // use_eot ?>
		<td data-name="use_eot"<?php echo $jam_kerja->use_eot->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_use_eot" class="jam_kerja_use_eot">
<span<?php echo $jam_kerja->use_eot->ViewAttributes() ?>>
<?php echo $jam_kerja->use_eot->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->min_eot->Visible) { // min_eot ?>
		<td data-name="min_eot"<?php echo $jam_kerja->min_eot->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_min_eot" class="jam_kerja_min_eot">
<span<?php echo $jam_kerja->min_eot->ViewAttributes() ?>>
<?php echo $jam_kerja->min_eot->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->max_eot->Visible) { // max_eot ?>
		<td data-name="max_eot"<?php echo $jam_kerja->max_eot->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_max_eot" class="jam_kerja_max_eot">
<span<?php echo $jam_kerja->max_eot->ViewAttributes() ?>>
<?php echo $jam_kerja->max_eot->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->reduce_eot->Visible) { // reduce_eot ?>
		<td data-name="reduce_eot"<?php echo $jam_kerja->reduce_eot->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_reduce_eot" class="jam_kerja_reduce_eot">
<span<?php echo $jam_kerja->reduce_eot->ViewAttributes() ?>>
<?php echo $jam_kerja->reduce_eot->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_durasi->Visible) { // jk_durasi ?>
		<td data-name="jk_durasi"<?php echo $jam_kerja->jk_durasi->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_durasi" class="jam_kerja_jk_durasi">
<span<?php echo $jam_kerja->jk_durasi->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_durasi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_countas->Visible) { // jk_countas ?>
		<td data-name="jk_countas"<?php echo $jam_kerja->jk_countas->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_countas" class="jam_kerja_jk_countas">
<span<?php echo $jam_kerja->jk_countas->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_countas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_min_countas->Visible) { // jk_min_countas ?>
		<td data-name="jk_min_countas"<?php echo $jam_kerja->jk_min_countas->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_min_countas" class="jam_kerja_jk_min_countas">
<span<?php echo $jam_kerja->jk_min_countas->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_min_countas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($jam_kerja->jk_ket->Visible) { // jk_ket ?>
		<td data-name="jk_ket"<?php echo $jam_kerja->jk_ket->CellAttributes() ?>>
<span id="el<?php echo $jam_kerja_list->RowCnt ?>_jam_kerja_jk_ket" class="jam_kerja_jk_ket">
<span<?php echo $jam_kerja->jk_ket->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ket->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$jam_kerja_list->ListOptions->Render("body", "right", $jam_kerja_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($jam_kerja->CurrentAction <> "gridadd")
		$jam_kerja_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($jam_kerja->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($jam_kerja_list->Recordset)
	$jam_kerja_list->Recordset->Close();
?>
<?php if ($jam_kerja->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($jam_kerja->CurrentAction <> "gridadd" && $jam_kerja->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($jam_kerja_list->Pager)) $jam_kerja_list->Pager = new cPrevNextPager($jam_kerja_list->StartRec, $jam_kerja_list->DisplayRecs, $jam_kerja_list->TotalRecs) ?>
<?php if ($jam_kerja_list->Pager->RecordCount > 0 && $jam_kerja_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($jam_kerja_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $jam_kerja_list->PageUrl() ?>start=<?php echo $jam_kerja_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($jam_kerja_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $jam_kerja_list->PageUrl() ?>start=<?php echo $jam_kerja_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $jam_kerja_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($jam_kerja_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $jam_kerja_list->PageUrl() ?>start=<?php echo $jam_kerja_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($jam_kerja_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $jam_kerja_list->PageUrl() ?>start=<?php echo $jam_kerja_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $jam_kerja_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $jam_kerja_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $jam_kerja_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $jam_kerja_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($jam_kerja_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($jam_kerja_list->TotalRecs == 0 && $jam_kerja->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($jam_kerja_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($jam_kerja->Export == "") { ?>
<script type="text/javascript">
fjam_kerjalistsrch.FilterList = <?php echo $jam_kerja_list->GetFilterList() ?>;
fjam_kerjalistsrch.Init();
fjam_kerjalist.Init();
</script>
<?php } ?>
<?php
$jam_kerja_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($jam_kerja->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$jam_kerja_list->Page_Terminate();
?>
