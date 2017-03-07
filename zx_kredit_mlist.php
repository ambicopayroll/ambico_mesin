<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "zx_kredit_minfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$zx_kredit_m_list = NULL; // Initialize page object first

class czx_kredit_m_list extends czx_kredit_m {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'zx_kredit_m';

	// Page object name
	var $PageObjName = 'zx_kredit_m_list';

	// Grid form hidden field names
	var $FormName = 'fzx_kredit_mlist';
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

		// Table object (zx_kredit_m)
		if (!isset($GLOBALS["zx_kredit_m"]) || get_class($GLOBALS["zx_kredit_m"]) == "czx_kredit_m") {
			$GLOBALS["zx_kredit_m"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["zx_kredit_m"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "zx_kredit_madd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "zx_kredit_mdelete.php";
		$this->MultiUpdateUrl = "zx_kredit_mupdate.php";

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'zx_kredit_m', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fzx_kredit_mlistsrch";

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
		$this->id_kredit->SetVisibility();
		$this->no_kredit->SetVisibility();
		$this->tgl_kredit->SetVisibility();
		$this->emp_id_auto->SetVisibility();
		$this->krd_id->SetVisibility();
		$this->cara_hitung->SetVisibility();
		$this->tot_kredit->SetVisibility();
		$this->saldo_aw->SetVisibility();
		$this->suku_bunga->SetVisibility();
		$this->periode_bulan->SetVisibility();
		$this->angs_pokok->SetVisibility();
		$this->angs_pertama->SetVisibility();
		$this->tot_debet->SetVisibility();
		$this->tot_angs_pokok->SetVisibility();
		$this->tot_bunga->SetVisibility();
		$this->def_pembulatan->SetVisibility();
		$this->jumlah_piutang->SetVisibility();
		$this->approv_by->SetVisibility();
		$this->status->SetVisibility();
		$this->status_lunas->SetVisibility();
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
		global $EW_EXPORT, $zx_kredit_m;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($zx_kredit_m);
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
			$this->id_kredit->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_kredit->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fzx_kredit_mlistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id_kredit->AdvancedSearch->ToJSON(), ","); // Field id_kredit
		$sFilterList = ew_Concat($sFilterList, $this->no_kredit->AdvancedSearch->ToJSON(), ","); // Field no_kredit
		$sFilterList = ew_Concat($sFilterList, $this->tgl_kredit->AdvancedSearch->ToJSON(), ","); // Field tgl_kredit
		$sFilterList = ew_Concat($sFilterList, $this->emp_id_auto->AdvancedSearch->ToJSON(), ","); // Field emp_id_auto
		$sFilterList = ew_Concat($sFilterList, $this->krd_id->AdvancedSearch->ToJSON(), ","); // Field krd_id
		$sFilterList = ew_Concat($sFilterList, $this->cara_hitung->AdvancedSearch->ToJSON(), ","); // Field cara_hitung
		$sFilterList = ew_Concat($sFilterList, $this->tot_kredit->AdvancedSearch->ToJSON(), ","); // Field tot_kredit
		$sFilterList = ew_Concat($sFilterList, $this->saldo_aw->AdvancedSearch->ToJSON(), ","); // Field saldo_aw
		$sFilterList = ew_Concat($sFilterList, $this->suku_bunga->AdvancedSearch->ToJSON(), ","); // Field suku_bunga
		$sFilterList = ew_Concat($sFilterList, $this->periode_bulan->AdvancedSearch->ToJSON(), ","); // Field periode_bulan
		$sFilterList = ew_Concat($sFilterList, $this->angs_pokok->AdvancedSearch->ToJSON(), ","); // Field angs_pokok
		$sFilterList = ew_Concat($sFilterList, $this->angs_pertama->AdvancedSearch->ToJSON(), ","); // Field angs_pertama
		$sFilterList = ew_Concat($sFilterList, $this->tot_debet->AdvancedSearch->ToJSON(), ","); // Field tot_debet
		$sFilterList = ew_Concat($sFilterList, $this->tot_angs_pokok->AdvancedSearch->ToJSON(), ","); // Field tot_angs_pokok
		$sFilterList = ew_Concat($sFilterList, $this->tot_bunga->AdvancedSearch->ToJSON(), ","); // Field tot_bunga
		$sFilterList = ew_Concat($sFilterList, $this->def_pembulatan->AdvancedSearch->ToJSON(), ","); // Field def_pembulatan
		$sFilterList = ew_Concat($sFilterList, $this->jumlah_piutang->AdvancedSearch->ToJSON(), ","); // Field jumlah_piutang
		$sFilterList = ew_Concat($sFilterList, $this->approv_by->AdvancedSearch->ToJSON(), ","); // Field approv_by
		$sFilterList = ew_Concat($sFilterList, $this->keterangan->AdvancedSearch->ToJSON(), ","); // Field keterangan
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJSON(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->status_lunas->AdvancedSearch->ToJSON(), ","); // Field status_lunas
		$sFilterList = ew_Concat($sFilterList, $this->lastupdate_date->AdvancedSearch->ToJSON(), ","); // Field lastupdate_date
		$sFilterList = ew_Concat($sFilterList, $this->lastupdate_user->AdvancedSearch->ToJSON(), ","); // Field lastupdate_user
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fzx_kredit_mlistsrch", $filters);

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

		// Field id_kredit
		$this->id_kredit->AdvancedSearch->SearchValue = @$filter["x_id_kredit"];
		$this->id_kredit->AdvancedSearch->SearchOperator = @$filter["z_id_kredit"];
		$this->id_kredit->AdvancedSearch->SearchCondition = @$filter["v_id_kredit"];
		$this->id_kredit->AdvancedSearch->SearchValue2 = @$filter["y_id_kredit"];
		$this->id_kredit->AdvancedSearch->SearchOperator2 = @$filter["w_id_kredit"];
		$this->id_kredit->AdvancedSearch->Save();

		// Field no_kredit
		$this->no_kredit->AdvancedSearch->SearchValue = @$filter["x_no_kredit"];
		$this->no_kredit->AdvancedSearch->SearchOperator = @$filter["z_no_kredit"];
		$this->no_kredit->AdvancedSearch->SearchCondition = @$filter["v_no_kredit"];
		$this->no_kredit->AdvancedSearch->SearchValue2 = @$filter["y_no_kredit"];
		$this->no_kredit->AdvancedSearch->SearchOperator2 = @$filter["w_no_kredit"];
		$this->no_kredit->AdvancedSearch->Save();

		// Field tgl_kredit
		$this->tgl_kredit->AdvancedSearch->SearchValue = @$filter["x_tgl_kredit"];
		$this->tgl_kredit->AdvancedSearch->SearchOperator = @$filter["z_tgl_kredit"];
		$this->tgl_kredit->AdvancedSearch->SearchCondition = @$filter["v_tgl_kredit"];
		$this->tgl_kredit->AdvancedSearch->SearchValue2 = @$filter["y_tgl_kredit"];
		$this->tgl_kredit->AdvancedSearch->SearchOperator2 = @$filter["w_tgl_kredit"];
		$this->tgl_kredit->AdvancedSearch->Save();

		// Field emp_id_auto
		$this->emp_id_auto->AdvancedSearch->SearchValue = @$filter["x_emp_id_auto"];
		$this->emp_id_auto->AdvancedSearch->SearchOperator = @$filter["z_emp_id_auto"];
		$this->emp_id_auto->AdvancedSearch->SearchCondition = @$filter["v_emp_id_auto"];
		$this->emp_id_auto->AdvancedSearch->SearchValue2 = @$filter["y_emp_id_auto"];
		$this->emp_id_auto->AdvancedSearch->SearchOperator2 = @$filter["w_emp_id_auto"];
		$this->emp_id_auto->AdvancedSearch->Save();

		// Field krd_id
		$this->krd_id->AdvancedSearch->SearchValue = @$filter["x_krd_id"];
		$this->krd_id->AdvancedSearch->SearchOperator = @$filter["z_krd_id"];
		$this->krd_id->AdvancedSearch->SearchCondition = @$filter["v_krd_id"];
		$this->krd_id->AdvancedSearch->SearchValue2 = @$filter["y_krd_id"];
		$this->krd_id->AdvancedSearch->SearchOperator2 = @$filter["w_krd_id"];
		$this->krd_id->AdvancedSearch->Save();

		// Field cara_hitung
		$this->cara_hitung->AdvancedSearch->SearchValue = @$filter["x_cara_hitung"];
		$this->cara_hitung->AdvancedSearch->SearchOperator = @$filter["z_cara_hitung"];
		$this->cara_hitung->AdvancedSearch->SearchCondition = @$filter["v_cara_hitung"];
		$this->cara_hitung->AdvancedSearch->SearchValue2 = @$filter["y_cara_hitung"];
		$this->cara_hitung->AdvancedSearch->SearchOperator2 = @$filter["w_cara_hitung"];
		$this->cara_hitung->AdvancedSearch->Save();

		// Field tot_kredit
		$this->tot_kredit->AdvancedSearch->SearchValue = @$filter["x_tot_kredit"];
		$this->tot_kredit->AdvancedSearch->SearchOperator = @$filter["z_tot_kredit"];
		$this->tot_kredit->AdvancedSearch->SearchCondition = @$filter["v_tot_kredit"];
		$this->tot_kredit->AdvancedSearch->SearchValue2 = @$filter["y_tot_kredit"];
		$this->tot_kredit->AdvancedSearch->SearchOperator2 = @$filter["w_tot_kredit"];
		$this->tot_kredit->AdvancedSearch->Save();

		// Field saldo_aw
		$this->saldo_aw->AdvancedSearch->SearchValue = @$filter["x_saldo_aw"];
		$this->saldo_aw->AdvancedSearch->SearchOperator = @$filter["z_saldo_aw"];
		$this->saldo_aw->AdvancedSearch->SearchCondition = @$filter["v_saldo_aw"];
		$this->saldo_aw->AdvancedSearch->SearchValue2 = @$filter["y_saldo_aw"];
		$this->saldo_aw->AdvancedSearch->SearchOperator2 = @$filter["w_saldo_aw"];
		$this->saldo_aw->AdvancedSearch->Save();

		// Field suku_bunga
		$this->suku_bunga->AdvancedSearch->SearchValue = @$filter["x_suku_bunga"];
		$this->suku_bunga->AdvancedSearch->SearchOperator = @$filter["z_suku_bunga"];
		$this->suku_bunga->AdvancedSearch->SearchCondition = @$filter["v_suku_bunga"];
		$this->suku_bunga->AdvancedSearch->SearchValue2 = @$filter["y_suku_bunga"];
		$this->suku_bunga->AdvancedSearch->SearchOperator2 = @$filter["w_suku_bunga"];
		$this->suku_bunga->AdvancedSearch->Save();

		// Field periode_bulan
		$this->periode_bulan->AdvancedSearch->SearchValue = @$filter["x_periode_bulan"];
		$this->periode_bulan->AdvancedSearch->SearchOperator = @$filter["z_periode_bulan"];
		$this->periode_bulan->AdvancedSearch->SearchCondition = @$filter["v_periode_bulan"];
		$this->periode_bulan->AdvancedSearch->SearchValue2 = @$filter["y_periode_bulan"];
		$this->periode_bulan->AdvancedSearch->SearchOperator2 = @$filter["w_periode_bulan"];
		$this->periode_bulan->AdvancedSearch->Save();

		// Field angs_pokok
		$this->angs_pokok->AdvancedSearch->SearchValue = @$filter["x_angs_pokok"];
		$this->angs_pokok->AdvancedSearch->SearchOperator = @$filter["z_angs_pokok"];
		$this->angs_pokok->AdvancedSearch->SearchCondition = @$filter["v_angs_pokok"];
		$this->angs_pokok->AdvancedSearch->SearchValue2 = @$filter["y_angs_pokok"];
		$this->angs_pokok->AdvancedSearch->SearchOperator2 = @$filter["w_angs_pokok"];
		$this->angs_pokok->AdvancedSearch->Save();

		// Field angs_pertama
		$this->angs_pertama->AdvancedSearch->SearchValue = @$filter["x_angs_pertama"];
		$this->angs_pertama->AdvancedSearch->SearchOperator = @$filter["z_angs_pertama"];
		$this->angs_pertama->AdvancedSearch->SearchCondition = @$filter["v_angs_pertama"];
		$this->angs_pertama->AdvancedSearch->SearchValue2 = @$filter["y_angs_pertama"];
		$this->angs_pertama->AdvancedSearch->SearchOperator2 = @$filter["w_angs_pertama"];
		$this->angs_pertama->AdvancedSearch->Save();

		// Field tot_debet
		$this->tot_debet->AdvancedSearch->SearchValue = @$filter["x_tot_debet"];
		$this->tot_debet->AdvancedSearch->SearchOperator = @$filter["z_tot_debet"];
		$this->tot_debet->AdvancedSearch->SearchCondition = @$filter["v_tot_debet"];
		$this->tot_debet->AdvancedSearch->SearchValue2 = @$filter["y_tot_debet"];
		$this->tot_debet->AdvancedSearch->SearchOperator2 = @$filter["w_tot_debet"];
		$this->tot_debet->AdvancedSearch->Save();

		// Field tot_angs_pokok
		$this->tot_angs_pokok->AdvancedSearch->SearchValue = @$filter["x_tot_angs_pokok"];
		$this->tot_angs_pokok->AdvancedSearch->SearchOperator = @$filter["z_tot_angs_pokok"];
		$this->tot_angs_pokok->AdvancedSearch->SearchCondition = @$filter["v_tot_angs_pokok"];
		$this->tot_angs_pokok->AdvancedSearch->SearchValue2 = @$filter["y_tot_angs_pokok"];
		$this->tot_angs_pokok->AdvancedSearch->SearchOperator2 = @$filter["w_tot_angs_pokok"];
		$this->tot_angs_pokok->AdvancedSearch->Save();

		// Field tot_bunga
		$this->tot_bunga->AdvancedSearch->SearchValue = @$filter["x_tot_bunga"];
		$this->tot_bunga->AdvancedSearch->SearchOperator = @$filter["z_tot_bunga"];
		$this->tot_bunga->AdvancedSearch->SearchCondition = @$filter["v_tot_bunga"];
		$this->tot_bunga->AdvancedSearch->SearchValue2 = @$filter["y_tot_bunga"];
		$this->tot_bunga->AdvancedSearch->SearchOperator2 = @$filter["w_tot_bunga"];
		$this->tot_bunga->AdvancedSearch->Save();

		// Field def_pembulatan
		$this->def_pembulatan->AdvancedSearch->SearchValue = @$filter["x_def_pembulatan"];
		$this->def_pembulatan->AdvancedSearch->SearchOperator = @$filter["z_def_pembulatan"];
		$this->def_pembulatan->AdvancedSearch->SearchCondition = @$filter["v_def_pembulatan"];
		$this->def_pembulatan->AdvancedSearch->SearchValue2 = @$filter["y_def_pembulatan"];
		$this->def_pembulatan->AdvancedSearch->SearchOperator2 = @$filter["w_def_pembulatan"];
		$this->def_pembulatan->AdvancedSearch->Save();

		// Field jumlah_piutang
		$this->jumlah_piutang->AdvancedSearch->SearchValue = @$filter["x_jumlah_piutang"];
		$this->jumlah_piutang->AdvancedSearch->SearchOperator = @$filter["z_jumlah_piutang"];
		$this->jumlah_piutang->AdvancedSearch->SearchCondition = @$filter["v_jumlah_piutang"];
		$this->jumlah_piutang->AdvancedSearch->SearchValue2 = @$filter["y_jumlah_piutang"];
		$this->jumlah_piutang->AdvancedSearch->SearchOperator2 = @$filter["w_jumlah_piutang"];
		$this->jumlah_piutang->AdvancedSearch->Save();

		// Field approv_by
		$this->approv_by->AdvancedSearch->SearchValue = @$filter["x_approv_by"];
		$this->approv_by->AdvancedSearch->SearchOperator = @$filter["z_approv_by"];
		$this->approv_by->AdvancedSearch->SearchCondition = @$filter["v_approv_by"];
		$this->approv_by->AdvancedSearch->SearchValue2 = @$filter["y_approv_by"];
		$this->approv_by->AdvancedSearch->SearchOperator2 = @$filter["w_approv_by"];
		$this->approv_by->AdvancedSearch->Save();

		// Field keterangan
		$this->keterangan->AdvancedSearch->SearchValue = @$filter["x_keterangan"];
		$this->keterangan->AdvancedSearch->SearchOperator = @$filter["z_keterangan"];
		$this->keterangan->AdvancedSearch->SearchCondition = @$filter["v_keterangan"];
		$this->keterangan->AdvancedSearch->SearchValue2 = @$filter["y_keterangan"];
		$this->keterangan->AdvancedSearch->SearchOperator2 = @$filter["w_keterangan"];
		$this->keterangan->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();

		// Field status_lunas
		$this->status_lunas->AdvancedSearch->SearchValue = @$filter["x_status_lunas"];
		$this->status_lunas->AdvancedSearch->SearchOperator = @$filter["z_status_lunas"];
		$this->status_lunas->AdvancedSearch->SearchCondition = @$filter["v_status_lunas"];
		$this->status_lunas->AdvancedSearch->SearchValue2 = @$filter["y_status_lunas"];
		$this->status_lunas->AdvancedSearch->SearchOperator2 = @$filter["w_status_lunas"];
		$this->status_lunas->AdvancedSearch->Save();

		// Field lastupdate_date
		$this->lastupdate_date->AdvancedSearch->SearchValue = @$filter["x_lastupdate_date"];
		$this->lastupdate_date->AdvancedSearch->SearchOperator = @$filter["z_lastupdate_date"];
		$this->lastupdate_date->AdvancedSearch->SearchCondition = @$filter["v_lastupdate_date"];
		$this->lastupdate_date->AdvancedSearch->SearchValue2 = @$filter["y_lastupdate_date"];
		$this->lastupdate_date->AdvancedSearch->SearchOperator2 = @$filter["w_lastupdate_date"];
		$this->lastupdate_date->AdvancedSearch->Save();

		// Field lastupdate_user
		$this->lastupdate_user->AdvancedSearch->SearchValue = @$filter["x_lastupdate_user"];
		$this->lastupdate_user->AdvancedSearch->SearchOperator = @$filter["z_lastupdate_user"];
		$this->lastupdate_user->AdvancedSearch->SearchCondition = @$filter["v_lastupdate_user"];
		$this->lastupdate_user->AdvancedSearch->SearchValue2 = @$filter["y_lastupdate_user"];
		$this->lastupdate_user->AdvancedSearch->SearchOperator2 = @$filter["w_lastupdate_user"];
		$this->lastupdate_user->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->no_kredit, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->approv_by, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->keterangan, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->lastupdate_user, $arKeywords, $type);
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
			$this->UpdateSort($this->id_kredit, $bCtrl); // id_kredit
			$this->UpdateSort($this->no_kredit, $bCtrl); // no_kredit
			$this->UpdateSort($this->tgl_kredit, $bCtrl); // tgl_kredit
			$this->UpdateSort($this->emp_id_auto, $bCtrl); // emp_id_auto
			$this->UpdateSort($this->krd_id, $bCtrl); // krd_id
			$this->UpdateSort($this->cara_hitung, $bCtrl); // cara_hitung
			$this->UpdateSort($this->tot_kredit, $bCtrl); // tot_kredit
			$this->UpdateSort($this->saldo_aw, $bCtrl); // saldo_aw
			$this->UpdateSort($this->suku_bunga, $bCtrl); // suku_bunga
			$this->UpdateSort($this->periode_bulan, $bCtrl); // periode_bulan
			$this->UpdateSort($this->angs_pokok, $bCtrl); // angs_pokok
			$this->UpdateSort($this->angs_pertama, $bCtrl); // angs_pertama
			$this->UpdateSort($this->tot_debet, $bCtrl); // tot_debet
			$this->UpdateSort($this->tot_angs_pokok, $bCtrl); // tot_angs_pokok
			$this->UpdateSort($this->tot_bunga, $bCtrl); // tot_bunga
			$this->UpdateSort($this->def_pembulatan, $bCtrl); // def_pembulatan
			$this->UpdateSort($this->jumlah_piutang, $bCtrl); // jumlah_piutang
			$this->UpdateSort($this->approv_by, $bCtrl); // approv_by
			$this->UpdateSort($this->status, $bCtrl); // status
			$this->UpdateSort($this->status_lunas, $bCtrl); // status_lunas
			$this->UpdateSort($this->lastupdate_date, $bCtrl); // lastupdate_date
			$this->UpdateSort($this->lastupdate_user, $bCtrl); // lastupdate_user
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
				$this->id_kredit->setSort("");
				$this->no_kredit->setSort("");
				$this->tgl_kredit->setSort("");
				$this->emp_id_auto->setSort("");
				$this->krd_id->setSort("");
				$this->cara_hitung->setSort("");
				$this->tot_kredit->setSort("");
				$this->saldo_aw->setSort("");
				$this->suku_bunga->setSort("");
				$this->periode_bulan->setSort("");
				$this->angs_pokok->setSort("");
				$this->angs_pertama->setSort("");
				$this->tot_debet->setSort("");
				$this->tot_angs_pokok->setSort("");
				$this->tot_bunga->setSort("");
				$this->def_pembulatan->setSort("");
				$this->jumlah_piutang->setSort("");
				$this->approv_by->setSort("");
				$this->status->setSort("");
				$this->status_lunas->setSort("");
				$this->lastupdate_date->setSort("");
				$this->lastupdate_user->setSort("");
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
		$this->ListOptions->UseButtonGroup = TRUE;
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_kredit->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fzx_kredit_mlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fzx_kredit_mlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fzx_kredit_mlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fzx_kredit_mlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->id_kredit->setDbValue($rs->fields('id_kredit'));
		$this->no_kredit->setDbValue($rs->fields('no_kredit'));
		$this->tgl_kredit->setDbValue($rs->fields('tgl_kredit'));
		$this->emp_id_auto->setDbValue($rs->fields('emp_id_auto'));
		$this->krd_id->setDbValue($rs->fields('krd_id'));
		$this->cara_hitung->setDbValue($rs->fields('cara_hitung'));
		$this->tot_kredit->setDbValue($rs->fields('tot_kredit'));
		$this->saldo_aw->setDbValue($rs->fields('saldo_aw'));
		$this->suku_bunga->setDbValue($rs->fields('suku_bunga'));
		$this->periode_bulan->setDbValue($rs->fields('periode_bulan'));
		$this->angs_pokok->setDbValue($rs->fields('angs_pokok'));
		$this->angs_pertama->setDbValue($rs->fields('angs_pertama'));
		$this->tot_debet->setDbValue($rs->fields('tot_debet'));
		$this->tot_angs_pokok->setDbValue($rs->fields('tot_angs_pokok'));
		$this->tot_bunga->setDbValue($rs->fields('tot_bunga'));
		$this->def_pembulatan->setDbValue($rs->fields('def_pembulatan'));
		$this->jumlah_piutang->setDbValue($rs->fields('jumlah_piutang'));
		$this->approv_by->setDbValue($rs->fields('approv_by'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->status->setDbValue($rs->fields('status'));
		$this->status_lunas->setDbValue($rs->fields('status_lunas'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_kredit->DbValue = $row['id_kredit'];
		$this->no_kredit->DbValue = $row['no_kredit'];
		$this->tgl_kredit->DbValue = $row['tgl_kredit'];
		$this->emp_id_auto->DbValue = $row['emp_id_auto'];
		$this->krd_id->DbValue = $row['krd_id'];
		$this->cara_hitung->DbValue = $row['cara_hitung'];
		$this->tot_kredit->DbValue = $row['tot_kredit'];
		$this->saldo_aw->DbValue = $row['saldo_aw'];
		$this->suku_bunga->DbValue = $row['suku_bunga'];
		$this->periode_bulan->DbValue = $row['periode_bulan'];
		$this->angs_pokok->DbValue = $row['angs_pokok'];
		$this->angs_pertama->DbValue = $row['angs_pertama'];
		$this->tot_debet->DbValue = $row['tot_debet'];
		$this->tot_angs_pokok->DbValue = $row['tot_angs_pokok'];
		$this->tot_bunga->DbValue = $row['tot_bunga'];
		$this->def_pembulatan->DbValue = $row['def_pembulatan'];
		$this->jumlah_piutang->DbValue = $row['jumlah_piutang'];
		$this->approv_by->DbValue = $row['approv_by'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->status->DbValue = $row['status'];
		$this->status_lunas->DbValue = $row['status_lunas'];
		$this->lastupdate_date->DbValue = $row['lastupdate_date'];
		$this->lastupdate_user->DbValue = $row['lastupdate_user'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_kredit")) <> "")
			$this->id_kredit->CurrentValue = $this->getKey("id_kredit"); // id_kredit
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
		if ($this->tot_kredit->FormValue == $this->tot_kredit->CurrentValue && is_numeric(ew_StrToFloat($this->tot_kredit->CurrentValue)))
			$this->tot_kredit->CurrentValue = ew_StrToFloat($this->tot_kredit->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo_aw->FormValue == $this->saldo_aw->CurrentValue && is_numeric(ew_StrToFloat($this->saldo_aw->CurrentValue)))
			$this->saldo_aw->CurrentValue = ew_StrToFloat($this->saldo_aw->CurrentValue);

		// Convert decimal values if posted back
		if ($this->suku_bunga->FormValue == $this->suku_bunga->CurrentValue && is_numeric(ew_StrToFloat($this->suku_bunga->CurrentValue)))
			$this->suku_bunga->CurrentValue = ew_StrToFloat($this->suku_bunga->CurrentValue);

		// Convert decimal values if posted back
		if ($this->angs_pokok->FormValue == $this->angs_pokok->CurrentValue && is_numeric(ew_StrToFloat($this->angs_pokok->CurrentValue)))
			$this->angs_pokok->CurrentValue = ew_StrToFloat($this->angs_pokok->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tot_debet->FormValue == $this->tot_debet->CurrentValue && is_numeric(ew_StrToFloat($this->tot_debet->CurrentValue)))
			$this->tot_debet->CurrentValue = ew_StrToFloat($this->tot_debet->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tot_angs_pokok->FormValue == $this->tot_angs_pokok->CurrentValue && is_numeric(ew_StrToFloat($this->tot_angs_pokok->CurrentValue)))
			$this->tot_angs_pokok->CurrentValue = ew_StrToFloat($this->tot_angs_pokok->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tot_bunga->FormValue == $this->tot_bunga->CurrentValue && is_numeric(ew_StrToFloat($this->tot_bunga->CurrentValue)))
			$this->tot_bunga->CurrentValue = ew_StrToFloat($this->tot_bunga->CurrentValue);

		// Convert decimal values if posted back
		if ($this->jumlah_piutang->FormValue == $this->jumlah_piutang->CurrentValue && is_numeric(ew_StrToFloat($this->jumlah_piutang->CurrentValue)))
			$this->jumlah_piutang->CurrentValue = ew_StrToFloat($this->jumlah_piutang->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_kredit
		// no_kredit
		// tgl_kredit
		// emp_id_auto
		// krd_id
		// cara_hitung
		// tot_kredit
		// saldo_aw
		// suku_bunga
		// periode_bulan
		// angs_pokok
		// angs_pertama
		// tot_debet
		// tot_angs_pokok
		// tot_bunga
		// def_pembulatan
		// jumlah_piutang
		// approv_by
		// keterangan
		// status
		// status_lunas
		// lastupdate_date
		// lastupdate_user

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_kredit
		$this->id_kredit->ViewValue = $this->id_kredit->CurrentValue;
		$this->id_kredit->ViewCustomAttributes = "";

		// no_kredit
		$this->no_kredit->ViewValue = $this->no_kredit->CurrentValue;
		$this->no_kredit->ViewCustomAttributes = "";

		// tgl_kredit
		$this->tgl_kredit->ViewValue = $this->tgl_kredit->CurrentValue;
		$this->tgl_kredit->ViewValue = ew_FormatDateTime($this->tgl_kredit->ViewValue, 0);
		$this->tgl_kredit->ViewCustomAttributes = "";

		// emp_id_auto
		$this->emp_id_auto->ViewValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->ViewCustomAttributes = "";

		// krd_id
		$this->krd_id->ViewValue = $this->krd_id->CurrentValue;
		$this->krd_id->ViewCustomAttributes = "";

		// cara_hitung
		$this->cara_hitung->ViewValue = $this->cara_hitung->CurrentValue;
		$this->cara_hitung->ViewCustomAttributes = "";

		// tot_kredit
		$this->tot_kredit->ViewValue = $this->tot_kredit->CurrentValue;
		$this->tot_kredit->ViewCustomAttributes = "";

		// saldo_aw
		$this->saldo_aw->ViewValue = $this->saldo_aw->CurrentValue;
		$this->saldo_aw->ViewCustomAttributes = "";

		// suku_bunga
		$this->suku_bunga->ViewValue = $this->suku_bunga->CurrentValue;
		$this->suku_bunga->ViewCustomAttributes = "";

		// periode_bulan
		$this->periode_bulan->ViewValue = $this->periode_bulan->CurrentValue;
		$this->periode_bulan->ViewCustomAttributes = "";

		// angs_pokok
		$this->angs_pokok->ViewValue = $this->angs_pokok->CurrentValue;
		$this->angs_pokok->ViewCustomAttributes = "";

		// angs_pertama
		$this->angs_pertama->ViewValue = $this->angs_pertama->CurrentValue;
		$this->angs_pertama->ViewValue = ew_FormatDateTime($this->angs_pertama->ViewValue, 0);
		$this->angs_pertama->ViewCustomAttributes = "";

		// tot_debet
		$this->tot_debet->ViewValue = $this->tot_debet->CurrentValue;
		$this->tot_debet->ViewCustomAttributes = "";

		// tot_angs_pokok
		$this->tot_angs_pokok->ViewValue = $this->tot_angs_pokok->CurrentValue;
		$this->tot_angs_pokok->ViewCustomAttributes = "";

		// tot_bunga
		$this->tot_bunga->ViewValue = $this->tot_bunga->CurrentValue;
		$this->tot_bunga->ViewCustomAttributes = "";

		// def_pembulatan
		$this->def_pembulatan->ViewValue = $this->def_pembulatan->CurrentValue;
		$this->def_pembulatan->ViewCustomAttributes = "";

		// jumlah_piutang
		$this->jumlah_piutang->ViewValue = $this->jumlah_piutang->CurrentValue;
		$this->jumlah_piutang->ViewCustomAttributes = "";

		// approv_by
		$this->approv_by->ViewValue = $this->approv_by->CurrentValue;
		$this->approv_by->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// status_lunas
		$this->status_lunas->ViewValue = $this->status_lunas->CurrentValue;
		$this->status_lunas->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

			// id_kredit
			$this->id_kredit->LinkCustomAttributes = "";
			$this->id_kredit->HrefValue = "";
			$this->id_kredit->TooltipValue = "";

			// no_kredit
			$this->no_kredit->LinkCustomAttributes = "";
			$this->no_kredit->HrefValue = "";
			$this->no_kredit->TooltipValue = "";

			// tgl_kredit
			$this->tgl_kredit->LinkCustomAttributes = "";
			$this->tgl_kredit->HrefValue = "";
			$this->tgl_kredit->TooltipValue = "";

			// emp_id_auto
			$this->emp_id_auto->LinkCustomAttributes = "";
			$this->emp_id_auto->HrefValue = "";
			$this->emp_id_auto->TooltipValue = "";

			// krd_id
			$this->krd_id->LinkCustomAttributes = "";
			$this->krd_id->HrefValue = "";
			$this->krd_id->TooltipValue = "";

			// cara_hitung
			$this->cara_hitung->LinkCustomAttributes = "";
			$this->cara_hitung->HrefValue = "";
			$this->cara_hitung->TooltipValue = "";

			// tot_kredit
			$this->tot_kredit->LinkCustomAttributes = "";
			$this->tot_kredit->HrefValue = "";
			$this->tot_kredit->TooltipValue = "";

			// saldo_aw
			$this->saldo_aw->LinkCustomAttributes = "";
			$this->saldo_aw->HrefValue = "";
			$this->saldo_aw->TooltipValue = "";

			// suku_bunga
			$this->suku_bunga->LinkCustomAttributes = "";
			$this->suku_bunga->HrefValue = "";
			$this->suku_bunga->TooltipValue = "";

			// periode_bulan
			$this->periode_bulan->LinkCustomAttributes = "";
			$this->periode_bulan->HrefValue = "";
			$this->periode_bulan->TooltipValue = "";

			// angs_pokok
			$this->angs_pokok->LinkCustomAttributes = "";
			$this->angs_pokok->HrefValue = "";
			$this->angs_pokok->TooltipValue = "";

			// angs_pertama
			$this->angs_pertama->LinkCustomAttributes = "";
			$this->angs_pertama->HrefValue = "";
			$this->angs_pertama->TooltipValue = "";

			// tot_debet
			$this->tot_debet->LinkCustomAttributes = "";
			$this->tot_debet->HrefValue = "";
			$this->tot_debet->TooltipValue = "";

			// tot_angs_pokok
			$this->tot_angs_pokok->LinkCustomAttributes = "";
			$this->tot_angs_pokok->HrefValue = "";
			$this->tot_angs_pokok->TooltipValue = "";

			// tot_bunga
			$this->tot_bunga->LinkCustomAttributes = "";
			$this->tot_bunga->HrefValue = "";
			$this->tot_bunga->TooltipValue = "";

			// def_pembulatan
			$this->def_pembulatan->LinkCustomAttributes = "";
			$this->def_pembulatan->HrefValue = "";
			$this->def_pembulatan->TooltipValue = "";

			// jumlah_piutang
			$this->jumlah_piutang->LinkCustomAttributes = "";
			$this->jumlah_piutang->HrefValue = "";
			$this->jumlah_piutang->TooltipValue = "";

			// approv_by
			$this->approv_by->LinkCustomAttributes = "";
			$this->approv_by->HrefValue = "";
			$this->approv_by->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// status_lunas
			$this->status_lunas->LinkCustomAttributes = "";
			$this->status_lunas->HrefValue = "";
			$this->status_lunas->TooltipValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";
			$this->lastupdate_date->TooltipValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
			$this->lastupdate_user->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_zx_kredit_m\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_zx_kredit_m',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fzx_kredit_mlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($zx_kredit_m_list)) $zx_kredit_m_list = new czx_kredit_m_list();

// Page init
$zx_kredit_m_list->Page_Init();

// Page main
$zx_kredit_m_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$zx_kredit_m_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($zx_kredit_m->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fzx_kredit_mlist = new ew_Form("fzx_kredit_mlist", "list");
fzx_kredit_mlist.FormKeyCountName = '<?php echo $zx_kredit_m_list->FormKeyCountName ?>';

// Form_CustomValidate event
fzx_kredit_mlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fzx_kredit_mlist.ValidateRequired = true;
<?php } else { ?>
fzx_kredit_mlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fzx_kredit_mlistsrch = new ew_Form("fzx_kredit_mlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($zx_kredit_m->Export == "") { ?>
<div class="ewToolbar">
<?php if ($zx_kredit_m->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($zx_kredit_m_list->TotalRecs > 0 && $zx_kredit_m_list->ExportOptions->Visible()) { ?>
<?php $zx_kredit_m_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($zx_kredit_m_list->SearchOptions->Visible()) { ?>
<?php $zx_kredit_m_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($zx_kredit_m_list->FilterOptions->Visible()) { ?>
<?php $zx_kredit_m_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($zx_kredit_m->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $zx_kredit_m_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($zx_kredit_m_list->TotalRecs <= 0)
			$zx_kredit_m_list->TotalRecs = $zx_kredit_m->SelectRecordCount();
	} else {
		if (!$zx_kredit_m_list->Recordset && ($zx_kredit_m_list->Recordset = $zx_kredit_m_list->LoadRecordset()))
			$zx_kredit_m_list->TotalRecs = $zx_kredit_m_list->Recordset->RecordCount();
	}
	$zx_kredit_m_list->StartRec = 1;
	if ($zx_kredit_m_list->DisplayRecs <= 0 || ($zx_kredit_m->Export <> "" && $zx_kredit_m->ExportAll)) // Display all records
		$zx_kredit_m_list->DisplayRecs = $zx_kredit_m_list->TotalRecs;
	if (!($zx_kredit_m->Export <> "" && $zx_kredit_m->ExportAll))
		$zx_kredit_m_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$zx_kredit_m_list->Recordset = $zx_kredit_m_list->LoadRecordset($zx_kredit_m_list->StartRec-1, $zx_kredit_m_list->DisplayRecs);

	// Set no record found message
	if ($zx_kredit_m->CurrentAction == "" && $zx_kredit_m_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$zx_kredit_m_list->setWarningMessage(ew_DeniedMsg());
		if ($zx_kredit_m_list->SearchWhere == "0=101")
			$zx_kredit_m_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$zx_kredit_m_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$zx_kredit_m_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($zx_kredit_m->Export == "" && $zx_kredit_m->CurrentAction == "") { ?>
<form name="fzx_kredit_mlistsrch" id="fzx_kredit_mlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($zx_kredit_m_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fzx_kredit_mlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="zx_kredit_m">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($zx_kredit_m_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($zx_kredit_m_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $zx_kredit_m_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($zx_kredit_m_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($zx_kredit_m_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($zx_kredit_m_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($zx_kredit_m_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $zx_kredit_m_list->ShowPageHeader(); ?>
<?php
$zx_kredit_m_list->ShowMessage();
?>
<?php if ($zx_kredit_m_list->TotalRecs > 0 || $zx_kredit_m->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid zx_kredit_m">
<form name="fzx_kredit_mlist" id="fzx_kredit_mlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($zx_kredit_m_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $zx_kredit_m_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="zx_kredit_m">
<div id="gmp_zx_kredit_m" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($zx_kredit_m_list->TotalRecs > 0 || $zx_kredit_m->CurrentAction == "gridedit") { ?>
<table id="tbl_zx_kredit_mlist" class="table ewTable">
<?php echo $zx_kredit_m->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$zx_kredit_m_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$zx_kredit_m_list->RenderListOptions();

// Render list options (header, left)
$zx_kredit_m_list->ListOptions->Render("header", "left");
?>
<?php if ($zx_kredit_m->id_kredit->Visible) { // id_kredit ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->id_kredit) == "") { ?>
		<th data-name="id_kredit"><div id="elh_zx_kredit_m_id_kredit" class="zx_kredit_m_id_kredit"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->id_kredit->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_kredit"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->id_kredit) ?>',2);"><div id="elh_zx_kredit_m_id_kredit" class="zx_kredit_m_id_kredit">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->id_kredit->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->id_kredit->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->id_kredit->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->no_kredit->Visible) { // no_kredit ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->no_kredit) == "") { ?>
		<th data-name="no_kredit"><div id="elh_zx_kredit_m_no_kredit" class="zx_kredit_m_no_kredit"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->no_kredit->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="no_kredit"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->no_kredit) ?>',2);"><div id="elh_zx_kredit_m_no_kredit" class="zx_kredit_m_no_kredit">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->no_kredit->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->no_kredit->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->no_kredit->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->tgl_kredit->Visible) { // tgl_kredit ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->tgl_kredit) == "") { ?>
		<th data-name="tgl_kredit"><div id="elh_zx_kredit_m_tgl_kredit" class="zx_kredit_m_tgl_kredit"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->tgl_kredit->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_kredit"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->tgl_kredit) ?>',2);"><div id="elh_zx_kredit_m_tgl_kredit" class="zx_kredit_m_tgl_kredit">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->tgl_kredit->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->tgl_kredit->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->tgl_kredit->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->emp_id_auto->Visible) { // emp_id_auto ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->emp_id_auto) == "") { ?>
		<th data-name="emp_id_auto"><div id="elh_zx_kredit_m_emp_id_auto" class="zx_kredit_m_emp_id_auto"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->emp_id_auto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="emp_id_auto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->emp_id_auto) ?>',2);"><div id="elh_zx_kredit_m_emp_id_auto" class="zx_kredit_m_emp_id_auto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->emp_id_auto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->emp_id_auto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->emp_id_auto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->krd_id->Visible) { // krd_id ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->krd_id) == "") { ?>
		<th data-name="krd_id"><div id="elh_zx_kredit_m_krd_id" class="zx_kredit_m_krd_id"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->krd_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="krd_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->krd_id) ?>',2);"><div id="elh_zx_kredit_m_krd_id" class="zx_kredit_m_krd_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->krd_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->krd_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->krd_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->cara_hitung->Visible) { // cara_hitung ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->cara_hitung) == "") { ?>
		<th data-name="cara_hitung"><div id="elh_zx_kredit_m_cara_hitung" class="zx_kredit_m_cara_hitung"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->cara_hitung->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cara_hitung"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->cara_hitung) ?>',2);"><div id="elh_zx_kredit_m_cara_hitung" class="zx_kredit_m_cara_hitung">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->cara_hitung->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->cara_hitung->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->cara_hitung->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->tot_kredit->Visible) { // tot_kredit ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->tot_kredit) == "") { ?>
		<th data-name="tot_kredit"><div id="elh_zx_kredit_m_tot_kredit" class="zx_kredit_m_tot_kredit"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->tot_kredit->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tot_kredit"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->tot_kredit) ?>',2);"><div id="elh_zx_kredit_m_tot_kredit" class="zx_kredit_m_tot_kredit">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->tot_kredit->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->tot_kredit->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->tot_kredit->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->saldo_aw->Visible) { // saldo_aw ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->saldo_aw) == "") { ?>
		<th data-name="saldo_aw"><div id="elh_zx_kredit_m_saldo_aw" class="zx_kredit_m_saldo_aw"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->saldo_aw->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo_aw"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->saldo_aw) ?>',2);"><div id="elh_zx_kredit_m_saldo_aw" class="zx_kredit_m_saldo_aw">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->saldo_aw->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->saldo_aw->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->saldo_aw->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->suku_bunga->Visible) { // suku_bunga ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->suku_bunga) == "") { ?>
		<th data-name="suku_bunga"><div id="elh_zx_kredit_m_suku_bunga" class="zx_kredit_m_suku_bunga"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->suku_bunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="suku_bunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->suku_bunga) ?>',2);"><div id="elh_zx_kredit_m_suku_bunga" class="zx_kredit_m_suku_bunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->suku_bunga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->suku_bunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->suku_bunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->periode_bulan->Visible) { // periode_bulan ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->periode_bulan) == "") { ?>
		<th data-name="periode_bulan"><div id="elh_zx_kredit_m_periode_bulan" class="zx_kredit_m_periode_bulan"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->periode_bulan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="periode_bulan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->periode_bulan) ?>',2);"><div id="elh_zx_kredit_m_periode_bulan" class="zx_kredit_m_periode_bulan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->periode_bulan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->periode_bulan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->periode_bulan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->angs_pokok->Visible) { // angs_pokok ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->angs_pokok) == "") { ?>
		<th data-name="angs_pokok"><div id="elh_zx_kredit_m_angs_pokok" class="zx_kredit_m_angs_pokok"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->angs_pokok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angs_pokok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->angs_pokok) ?>',2);"><div id="elh_zx_kredit_m_angs_pokok" class="zx_kredit_m_angs_pokok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->angs_pokok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->angs_pokok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->angs_pokok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->angs_pertama->Visible) { // angs_pertama ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->angs_pertama) == "") { ?>
		<th data-name="angs_pertama"><div id="elh_zx_kredit_m_angs_pertama" class="zx_kredit_m_angs_pertama"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->angs_pertama->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angs_pertama"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->angs_pertama) ?>',2);"><div id="elh_zx_kredit_m_angs_pertama" class="zx_kredit_m_angs_pertama">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->angs_pertama->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->angs_pertama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->angs_pertama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->tot_debet->Visible) { // tot_debet ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->tot_debet) == "") { ?>
		<th data-name="tot_debet"><div id="elh_zx_kredit_m_tot_debet" class="zx_kredit_m_tot_debet"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->tot_debet->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tot_debet"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->tot_debet) ?>',2);"><div id="elh_zx_kredit_m_tot_debet" class="zx_kredit_m_tot_debet">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->tot_debet->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->tot_debet->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->tot_debet->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->tot_angs_pokok->Visible) { // tot_angs_pokok ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->tot_angs_pokok) == "") { ?>
		<th data-name="tot_angs_pokok"><div id="elh_zx_kredit_m_tot_angs_pokok" class="zx_kredit_m_tot_angs_pokok"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->tot_angs_pokok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tot_angs_pokok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->tot_angs_pokok) ?>',2);"><div id="elh_zx_kredit_m_tot_angs_pokok" class="zx_kredit_m_tot_angs_pokok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->tot_angs_pokok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->tot_angs_pokok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->tot_angs_pokok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->tot_bunga->Visible) { // tot_bunga ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->tot_bunga) == "") { ?>
		<th data-name="tot_bunga"><div id="elh_zx_kredit_m_tot_bunga" class="zx_kredit_m_tot_bunga"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->tot_bunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tot_bunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->tot_bunga) ?>',2);"><div id="elh_zx_kredit_m_tot_bunga" class="zx_kredit_m_tot_bunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->tot_bunga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->tot_bunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->tot_bunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->def_pembulatan->Visible) { // def_pembulatan ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->def_pembulatan) == "") { ?>
		<th data-name="def_pembulatan"><div id="elh_zx_kredit_m_def_pembulatan" class="zx_kredit_m_def_pembulatan"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->def_pembulatan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="def_pembulatan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->def_pembulatan) ?>',2);"><div id="elh_zx_kredit_m_def_pembulatan" class="zx_kredit_m_def_pembulatan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->def_pembulatan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->def_pembulatan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->def_pembulatan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->jumlah_piutang->Visible) { // jumlah_piutang ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->jumlah_piutang) == "") { ?>
		<th data-name="jumlah_piutang"><div id="elh_zx_kredit_m_jumlah_piutang" class="zx_kredit_m_jumlah_piutang"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->jumlah_piutang->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jumlah_piutang"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->jumlah_piutang) ?>',2);"><div id="elh_zx_kredit_m_jumlah_piutang" class="zx_kredit_m_jumlah_piutang">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->jumlah_piutang->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->jumlah_piutang->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->jumlah_piutang->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->approv_by->Visible) { // approv_by ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->approv_by) == "") { ?>
		<th data-name="approv_by"><div id="elh_zx_kredit_m_approv_by" class="zx_kredit_m_approv_by"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->approv_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="approv_by"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->approv_by) ?>',2);"><div id="elh_zx_kredit_m_approv_by" class="zx_kredit_m_approv_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->approv_by->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->approv_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->approv_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->status->Visible) { // status ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->status) == "") { ?>
		<th data-name="status"><div id="elh_zx_kredit_m_status" class="zx_kredit_m_status"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->status) ?>',2);"><div id="elh_zx_kredit_m_status" class="zx_kredit_m_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->status_lunas->Visible) { // status_lunas ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->status_lunas) == "") { ?>
		<th data-name="status_lunas"><div id="elh_zx_kredit_m_status_lunas" class="zx_kredit_m_status_lunas"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->status_lunas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status_lunas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->status_lunas) ?>',2);"><div id="elh_zx_kredit_m_status_lunas" class="zx_kredit_m_status_lunas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->status_lunas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->status_lunas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->status_lunas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->lastupdate_date->Visible) { // lastupdate_date ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->lastupdate_date) == "") { ?>
		<th data-name="lastupdate_date"><div id="elh_zx_kredit_m_lastupdate_date" class="zx_kredit_m_lastupdate_date"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->lastupdate_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lastupdate_date"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->lastupdate_date) ?>',2);"><div id="elh_zx_kredit_m_lastupdate_date" class="zx_kredit_m_lastupdate_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->lastupdate_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->lastupdate_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->lastupdate_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_kredit_m->lastupdate_user->Visible) { // lastupdate_user ?>
	<?php if ($zx_kredit_m->SortUrl($zx_kredit_m->lastupdate_user) == "") { ?>
		<th data-name="lastupdate_user"><div id="elh_zx_kredit_m_lastupdate_user" class="zx_kredit_m_lastupdate_user"><div class="ewTableHeaderCaption"><?php echo $zx_kredit_m->lastupdate_user->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lastupdate_user"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_kredit_m->SortUrl($zx_kredit_m->lastupdate_user) ?>',2);"><div id="elh_zx_kredit_m_lastupdate_user" class="zx_kredit_m_lastupdate_user">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_kredit_m->lastupdate_user->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($zx_kredit_m->lastupdate_user->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_kredit_m->lastupdate_user->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$zx_kredit_m_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($zx_kredit_m->ExportAll && $zx_kredit_m->Export <> "") {
	$zx_kredit_m_list->StopRec = $zx_kredit_m_list->TotalRecs;
} else {

	// Set the last record to display
	if ($zx_kredit_m_list->TotalRecs > $zx_kredit_m_list->StartRec + $zx_kredit_m_list->DisplayRecs - 1)
		$zx_kredit_m_list->StopRec = $zx_kredit_m_list->StartRec + $zx_kredit_m_list->DisplayRecs - 1;
	else
		$zx_kredit_m_list->StopRec = $zx_kredit_m_list->TotalRecs;
}
$zx_kredit_m_list->RecCnt = $zx_kredit_m_list->StartRec - 1;
if ($zx_kredit_m_list->Recordset && !$zx_kredit_m_list->Recordset->EOF) {
	$zx_kredit_m_list->Recordset->MoveFirst();
	$bSelectLimit = $zx_kredit_m_list->UseSelectLimit;
	if (!$bSelectLimit && $zx_kredit_m_list->StartRec > 1)
		$zx_kredit_m_list->Recordset->Move($zx_kredit_m_list->StartRec - 1);
} elseif (!$zx_kredit_m->AllowAddDeleteRow && $zx_kredit_m_list->StopRec == 0) {
	$zx_kredit_m_list->StopRec = $zx_kredit_m->GridAddRowCount;
}

// Initialize aggregate
$zx_kredit_m->RowType = EW_ROWTYPE_AGGREGATEINIT;
$zx_kredit_m->ResetAttrs();
$zx_kredit_m_list->RenderRow();
while ($zx_kredit_m_list->RecCnt < $zx_kredit_m_list->StopRec) {
	$zx_kredit_m_list->RecCnt++;
	if (intval($zx_kredit_m_list->RecCnt) >= intval($zx_kredit_m_list->StartRec)) {
		$zx_kredit_m_list->RowCnt++;

		// Set up key count
		$zx_kredit_m_list->KeyCount = $zx_kredit_m_list->RowIndex;

		// Init row class and style
		$zx_kredit_m->ResetAttrs();
		$zx_kredit_m->CssClass = "";
		if ($zx_kredit_m->CurrentAction == "gridadd") {
		} else {
			$zx_kredit_m_list->LoadRowValues($zx_kredit_m_list->Recordset); // Load row values
		}
		$zx_kredit_m->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$zx_kredit_m->RowAttrs = array_merge($zx_kredit_m->RowAttrs, array('data-rowindex'=>$zx_kredit_m_list->RowCnt, 'id'=>'r' . $zx_kredit_m_list->RowCnt . '_zx_kredit_m', 'data-rowtype'=>$zx_kredit_m->RowType));

		// Render row
		$zx_kredit_m_list->RenderRow();

		// Render list options
		$zx_kredit_m_list->RenderListOptions();
?>
	<tr<?php echo $zx_kredit_m->RowAttributes() ?>>
<?php

// Render list options (body, left)
$zx_kredit_m_list->ListOptions->Render("body", "left", $zx_kredit_m_list->RowCnt);
?>
	<?php if ($zx_kredit_m->id_kredit->Visible) { // id_kredit ?>
		<td data-name="id_kredit"<?php echo $zx_kredit_m->id_kredit->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_id_kredit" class="zx_kredit_m_id_kredit">
<span<?php echo $zx_kredit_m->id_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_m->id_kredit->ListViewValue() ?></span>
</span>
<a id="<?php echo $zx_kredit_m_list->PageObjName . "_row_" . $zx_kredit_m_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($zx_kredit_m->no_kredit->Visible) { // no_kredit ?>
		<td data-name="no_kredit"<?php echo $zx_kredit_m->no_kredit->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_no_kredit" class="zx_kredit_m_no_kredit">
<span<?php echo $zx_kredit_m->no_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_m->no_kredit->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->tgl_kredit->Visible) { // tgl_kredit ?>
		<td data-name="tgl_kredit"<?php echo $zx_kredit_m->tgl_kredit->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_tgl_kredit" class="zx_kredit_m_tgl_kredit">
<span<?php echo $zx_kredit_m->tgl_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tgl_kredit->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->emp_id_auto->Visible) { // emp_id_auto ?>
		<td data-name="emp_id_auto"<?php echo $zx_kredit_m->emp_id_auto->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_emp_id_auto" class="zx_kredit_m_emp_id_auto">
<span<?php echo $zx_kredit_m->emp_id_auto->ViewAttributes() ?>>
<?php echo $zx_kredit_m->emp_id_auto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->krd_id->Visible) { // krd_id ?>
		<td data-name="krd_id"<?php echo $zx_kredit_m->krd_id->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_krd_id" class="zx_kredit_m_krd_id">
<span<?php echo $zx_kredit_m->krd_id->ViewAttributes() ?>>
<?php echo $zx_kredit_m->krd_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->cara_hitung->Visible) { // cara_hitung ?>
		<td data-name="cara_hitung"<?php echo $zx_kredit_m->cara_hitung->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_cara_hitung" class="zx_kredit_m_cara_hitung">
<span<?php echo $zx_kredit_m->cara_hitung->ViewAttributes() ?>>
<?php echo $zx_kredit_m->cara_hitung->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->tot_kredit->Visible) { // tot_kredit ?>
		<td data-name="tot_kredit"<?php echo $zx_kredit_m->tot_kredit->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_tot_kredit" class="zx_kredit_m_tot_kredit">
<span<?php echo $zx_kredit_m->tot_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tot_kredit->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->saldo_aw->Visible) { // saldo_aw ?>
		<td data-name="saldo_aw"<?php echo $zx_kredit_m->saldo_aw->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_saldo_aw" class="zx_kredit_m_saldo_aw">
<span<?php echo $zx_kredit_m->saldo_aw->ViewAttributes() ?>>
<?php echo $zx_kredit_m->saldo_aw->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->suku_bunga->Visible) { // suku_bunga ?>
		<td data-name="suku_bunga"<?php echo $zx_kredit_m->suku_bunga->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_suku_bunga" class="zx_kredit_m_suku_bunga">
<span<?php echo $zx_kredit_m->suku_bunga->ViewAttributes() ?>>
<?php echo $zx_kredit_m->suku_bunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->periode_bulan->Visible) { // periode_bulan ?>
		<td data-name="periode_bulan"<?php echo $zx_kredit_m->periode_bulan->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_periode_bulan" class="zx_kredit_m_periode_bulan">
<span<?php echo $zx_kredit_m->periode_bulan->ViewAttributes() ?>>
<?php echo $zx_kredit_m->periode_bulan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->angs_pokok->Visible) { // angs_pokok ?>
		<td data-name="angs_pokok"<?php echo $zx_kredit_m->angs_pokok->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_angs_pokok" class="zx_kredit_m_angs_pokok">
<span<?php echo $zx_kredit_m->angs_pokok->ViewAttributes() ?>>
<?php echo $zx_kredit_m->angs_pokok->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->angs_pertama->Visible) { // angs_pertama ?>
		<td data-name="angs_pertama"<?php echo $zx_kredit_m->angs_pertama->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_angs_pertama" class="zx_kredit_m_angs_pertama">
<span<?php echo $zx_kredit_m->angs_pertama->ViewAttributes() ?>>
<?php echo $zx_kredit_m->angs_pertama->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->tot_debet->Visible) { // tot_debet ?>
		<td data-name="tot_debet"<?php echo $zx_kredit_m->tot_debet->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_tot_debet" class="zx_kredit_m_tot_debet">
<span<?php echo $zx_kredit_m->tot_debet->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tot_debet->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->tot_angs_pokok->Visible) { // tot_angs_pokok ?>
		<td data-name="tot_angs_pokok"<?php echo $zx_kredit_m->tot_angs_pokok->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_tot_angs_pokok" class="zx_kredit_m_tot_angs_pokok">
<span<?php echo $zx_kredit_m->tot_angs_pokok->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tot_angs_pokok->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->tot_bunga->Visible) { // tot_bunga ?>
		<td data-name="tot_bunga"<?php echo $zx_kredit_m->tot_bunga->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_tot_bunga" class="zx_kredit_m_tot_bunga">
<span<?php echo $zx_kredit_m->tot_bunga->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tot_bunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->def_pembulatan->Visible) { // def_pembulatan ?>
		<td data-name="def_pembulatan"<?php echo $zx_kredit_m->def_pembulatan->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_def_pembulatan" class="zx_kredit_m_def_pembulatan">
<span<?php echo $zx_kredit_m->def_pembulatan->ViewAttributes() ?>>
<?php echo $zx_kredit_m->def_pembulatan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->jumlah_piutang->Visible) { // jumlah_piutang ?>
		<td data-name="jumlah_piutang"<?php echo $zx_kredit_m->jumlah_piutang->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_jumlah_piutang" class="zx_kredit_m_jumlah_piutang">
<span<?php echo $zx_kredit_m->jumlah_piutang->ViewAttributes() ?>>
<?php echo $zx_kredit_m->jumlah_piutang->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->approv_by->Visible) { // approv_by ?>
		<td data-name="approv_by"<?php echo $zx_kredit_m->approv_by->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_approv_by" class="zx_kredit_m_approv_by">
<span<?php echo $zx_kredit_m->approv_by->ViewAttributes() ?>>
<?php echo $zx_kredit_m->approv_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->status->Visible) { // status ?>
		<td data-name="status"<?php echo $zx_kredit_m->status->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_status" class="zx_kredit_m_status">
<span<?php echo $zx_kredit_m->status->ViewAttributes() ?>>
<?php echo $zx_kredit_m->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->status_lunas->Visible) { // status_lunas ?>
		<td data-name="status_lunas"<?php echo $zx_kredit_m->status_lunas->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_status_lunas" class="zx_kredit_m_status_lunas">
<span<?php echo $zx_kredit_m->status_lunas->ViewAttributes() ?>>
<?php echo $zx_kredit_m->status_lunas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->lastupdate_date->Visible) { // lastupdate_date ?>
		<td data-name="lastupdate_date"<?php echo $zx_kredit_m->lastupdate_date->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_lastupdate_date" class="zx_kredit_m_lastupdate_date">
<span<?php echo $zx_kredit_m->lastupdate_date->ViewAttributes() ?>>
<?php echo $zx_kredit_m->lastupdate_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_kredit_m->lastupdate_user->Visible) { // lastupdate_user ?>
		<td data-name="lastupdate_user"<?php echo $zx_kredit_m->lastupdate_user->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_list->RowCnt ?>_zx_kredit_m_lastupdate_user" class="zx_kredit_m_lastupdate_user">
<span<?php echo $zx_kredit_m->lastupdate_user->ViewAttributes() ?>>
<?php echo $zx_kredit_m->lastupdate_user->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$zx_kredit_m_list->ListOptions->Render("body", "right", $zx_kredit_m_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($zx_kredit_m->CurrentAction <> "gridadd")
		$zx_kredit_m_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($zx_kredit_m->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($zx_kredit_m_list->Recordset)
	$zx_kredit_m_list->Recordset->Close();
?>
<?php if ($zx_kredit_m->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($zx_kredit_m->CurrentAction <> "gridadd" && $zx_kredit_m->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($zx_kredit_m_list->Pager)) $zx_kredit_m_list->Pager = new cPrevNextPager($zx_kredit_m_list->StartRec, $zx_kredit_m_list->DisplayRecs, $zx_kredit_m_list->TotalRecs) ?>
<?php if ($zx_kredit_m_list->Pager->RecordCount > 0 && $zx_kredit_m_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($zx_kredit_m_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $zx_kredit_m_list->PageUrl() ?>start=<?php echo $zx_kredit_m_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($zx_kredit_m_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $zx_kredit_m_list->PageUrl() ?>start=<?php echo $zx_kredit_m_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $zx_kredit_m_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($zx_kredit_m_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $zx_kredit_m_list->PageUrl() ?>start=<?php echo $zx_kredit_m_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($zx_kredit_m_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $zx_kredit_m_list->PageUrl() ?>start=<?php echo $zx_kredit_m_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $zx_kredit_m_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $zx_kredit_m_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $zx_kredit_m_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $zx_kredit_m_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($zx_kredit_m_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($zx_kredit_m_list->TotalRecs == 0 && $zx_kredit_m->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($zx_kredit_m_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($zx_kredit_m->Export == "") { ?>
<script type="text/javascript">
fzx_kredit_mlistsrch.FilterList = <?php echo $zx_kredit_m_list->GetFilterList() ?>;
fzx_kredit_mlistsrch.Init();
fzx_kredit_mlist.Init();
</script>
<?php } ?>
<?php
$zx_kredit_m_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($zx_kredit_m->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$zx_kredit_m_list->Page_Terminate();
?>
