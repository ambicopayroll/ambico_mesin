<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "zx_bayar_kreditinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$zx_bayar_kredit_list = NULL; // Initialize page object first

class czx_bayar_kredit_list extends czx_bayar_kredit {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'zx_bayar_kredit';

	// Page object name
	var $PageObjName = 'zx_bayar_kredit_list';

	// Grid form hidden field names
	var $FormName = 'fzx_bayar_kreditlist';
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

		// Table object (zx_bayar_kredit)
		if (!isset($GLOBALS["zx_bayar_kredit"]) || get_class($GLOBALS["zx_bayar_kredit"]) == "czx_bayar_kredit") {
			$GLOBALS["zx_bayar_kredit"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["zx_bayar_kredit"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "zx_bayar_kreditadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "zx_bayar_kreditdelete.php";
		$this->MultiUpdateUrl = "zx_bayar_kreditupdate.php";

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'zx_bayar_kredit', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fzx_bayar_kreditlistsrch";

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
		$this->id_bayar->SetVisibility();
		$this->tgl_bayar->SetVisibility();
		$this->id_kredit->SetVisibility();
		$this->no_urut->SetVisibility();
		$this->tgl_jt->SetVisibility();
		$this->debet->SetVisibility();
		$this->angs_pokok->SetVisibility();
		$this->bunga->SetVisibility();
		$this->emp_id_auto->SetVisibility();
		$this->status->SetVisibility();
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
		global $EW_EXPORT, $zx_bayar_kredit;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($zx_bayar_kredit);
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
			$this->id_bayar->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_bayar->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fzx_bayar_kreditlistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id_bayar->AdvancedSearch->ToJSON(), ","); // Field id_bayar
		$sFilterList = ew_Concat($sFilterList, $this->tgl_bayar->AdvancedSearch->ToJSON(), ","); // Field tgl_bayar
		$sFilterList = ew_Concat($sFilterList, $this->id_kredit->AdvancedSearch->ToJSON(), ","); // Field id_kredit
		$sFilterList = ew_Concat($sFilterList, $this->no_urut->AdvancedSearch->ToJSON(), ","); // Field no_urut
		$sFilterList = ew_Concat($sFilterList, $this->tgl_jt->AdvancedSearch->ToJSON(), ","); // Field tgl_jt
		$sFilterList = ew_Concat($sFilterList, $this->debet->AdvancedSearch->ToJSON(), ","); // Field debet
		$sFilterList = ew_Concat($sFilterList, $this->angs_pokok->AdvancedSearch->ToJSON(), ","); // Field angs_pokok
		$sFilterList = ew_Concat($sFilterList, $this->bunga->AdvancedSearch->ToJSON(), ","); // Field bunga
		$sFilterList = ew_Concat($sFilterList, $this->emp_id_auto->AdvancedSearch->ToJSON(), ","); // Field emp_id_auto
		$sFilterList = ew_Concat($sFilterList, $this->keterangan->AdvancedSearch->ToJSON(), ","); // Field keterangan
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJSON(), ","); // Field status
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fzx_bayar_kreditlistsrch", $filters);

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

		// Field id_bayar
		$this->id_bayar->AdvancedSearch->SearchValue = @$filter["x_id_bayar"];
		$this->id_bayar->AdvancedSearch->SearchOperator = @$filter["z_id_bayar"];
		$this->id_bayar->AdvancedSearch->SearchCondition = @$filter["v_id_bayar"];
		$this->id_bayar->AdvancedSearch->SearchValue2 = @$filter["y_id_bayar"];
		$this->id_bayar->AdvancedSearch->SearchOperator2 = @$filter["w_id_bayar"];
		$this->id_bayar->AdvancedSearch->Save();

		// Field tgl_bayar
		$this->tgl_bayar->AdvancedSearch->SearchValue = @$filter["x_tgl_bayar"];
		$this->tgl_bayar->AdvancedSearch->SearchOperator = @$filter["z_tgl_bayar"];
		$this->tgl_bayar->AdvancedSearch->SearchCondition = @$filter["v_tgl_bayar"];
		$this->tgl_bayar->AdvancedSearch->SearchValue2 = @$filter["y_tgl_bayar"];
		$this->tgl_bayar->AdvancedSearch->SearchOperator2 = @$filter["w_tgl_bayar"];
		$this->tgl_bayar->AdvancedSearch->Save();

		// Field id_kredit
		$this->id_kredit->AdvancedSearch->SearchValue = @$filter["x_id_kredit"];
		$this->id_kredit->AdvancedSearch->SearchOperator = @$filter["z_id_kredit"];
		$this->id_kredit->AdvancedSearch->SearchCondition = @$filter["v_id_kredit"];
		$this->id_kredit->AdvancedSearch->SearchValue2 = @$filter["y_id_kredit"];
		$this->id_kredit->AdvancedSearch->SearchOperator2 = @$filter["w_id_kredit"];
		$this->id_kredit->AdvancedSearch->Save();

		// Field no_urut
		$this->no_urut->AdvancedSearch->SearchValue = @$filter["x_no_urut"];
		$this->no_urut->AdvancedSearch->SearchOperator = @$filter["z_no_urut"];
		$this->no_urut->AdvancedSearch->SearchCondition = @$filter["v_no_urut"];
		$this->no_urut->AdvancedSearch->SearchValue2 = @$filter["y_no_urut"];
		$this->no_urut->AdvancedSearch->SearchOperator2 = @$filter["w_no_urut"];
		$this->no_urut->AdvancedSearch->Save();

		// Field tgl_jt
		$this->tgl_jt->AdvancedSearch->SearchValue = @$filter["x_tgl_jt"];
		$this->tgl_jt->AdvancedSearch->SearchOperator = @$filter["z_tgl_jt"];
		$this->tgl_jt->AdvancedSearch->SearchCondition = @$filter["v_tgl_jt"];
		$this->tgl_jt->AdvancedSearch->SearchValue2 = @$filter["y_tgl_jt"];
		$this->tgl_jt->AdvancedSearch->SearchOperator2 = @$filter["w_tgl_jt"];
		$this->tgl_jt->AdvancedSearch->Save();

		// Field debet
		$this->debet->AdvancedSearch->SearchValue = @$filter["x_debet"];
		$this->debet->AdvancedSearch->SearchOperator = @$filter["z_debet"];
		$this->debet->AdvancedSearch->SearchCondition = @$filter["v_debet"];
		$this->debet->AdvancedSearch->SearchValue2 = @$filter["y_debet"];
		$this->debet->AdvancedSearch->SearchOperator2 = @$filter["w_debet"];
		$this->debet->AdvancedSearch->Save();

		// Field angs_pokok
		$this->angs_pokok->AdvancedSearch->SearchValue = @$filter["x_angs_pokok"];
		$this->angs_pokok->AdvancedSearch->SearchOperator = @$filter["z_angs_pokok"];
		$this->angs_pokok->AdvancedSearch->SearchCondition = @$filter["v_angs_pokok"];
		$this->angs_pokok->AdvancedSearch->SearchValue2 = @$filter["y_angs_pokok"];
		$this->angs_pokok->AdvancedSearch->SearchOperator2 = @$filter["w_angs_pokok"];
		$this->angs_pokok->AdvancedSearch->Save();

		// Field bunga
		$this->bunga->AdvancedSearch->SearchValue = @$filter["x_bunga"];
		$this->bunga->AdvancedSearch->SearchOperator = @$filter["z_bunga"];
		$this->bunga->AdvancedSearch->SearchCondition = @$filter["v_bunga"];
		$this->bunga->AdvancedSearch->SearchValue2 = @$filter["y_bunga"];
		$this->bunga->AdvancedSearch->SearchOperator2 = @$filter["w_bunga"];
		$this->bunga->AdvancedSearch->Save();

		// Field emp_id_auto
		$this->emp_id_auto->AdvancedSearch->SearchValue = @$filter["x_emp_id_auto"];
		$this->emp_id_auto->AdvancedSearch->SearchOperator = @$filter["z_emp_id_auto"];
		$this->emp_id_auto->AdvancedSearch->SearchCondition = @$filter["v_emp_id_auto"];
		$this->emp_id_auto->AdvancedSearch->SearchValue2 = @$filter["y_emp_id_auto"];
		$this->emp_id_auto->AdvancedSearch->SearchOperator2 = @$filter["w_emp_id_auto"];
		$this->emp_id_auto->AdvancedSearch->Save();

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
		$this->BuildBasicSearchSQL($sWhere, $this->keterangan, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->lastupdate_user, $arKeywords, $type);
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
			$this->UpdateSort($this->id_bayar, $bCtrl); // id_bayar
			$this->UpdateSort($this->tgl_bayar, $bCtrl); // tgl_bayar
			$this->UpdateSort($this->id_kredit, $bCtrl); // id_kredit
			$this->UpdateSort($this->no_urut, $bCtrl); // no_urut
			$this->UpdateSort($this->tgl_jt, $bCtrl); // tgl_jt
			$this->UpdateSort($this->debet, $bCtrl); // debet
			$this->UpdateSort($this->angs_pokok, $bCtrl); // angs_pokok
			$this->UpdateSort($this->bunga, $bCtrl); // bunga
			$this->UpdateSort($this->emp_id_auto, $bCtrl); // emp_id_auto
			$this->UpdateSort($this->status, $bCtrl); // status
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
				$this->id_bayar->setSort("");
				$this->tgl_bayar->setSort("");
				$this->id_kredit->setSort("");
				$this->no_urut->setSort("");
				$this->tgl_jt->setSort("");
				$this->debet->setSort("");
				$this->angs_pokok->setSort("");
				$this->bunga->setSort("");
				$this->emp_id_auto->setSort("");
				$this->status->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_bayar->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fzx_bayar_kreditlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fzx_bayar_kreditlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fzx_bayar_kreditlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fzx_bayar_kreditlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->id_bayar->setDbValue($rs->fields('id_bayar'));
		$this->tgl_bayar->setDbValue($rs->fields('tgl_bayar'));
		$this->id_kredit->setDbValue($rs->fields('id_kredit'));
		$this->no_urut->setDbValue($rs->fields('no_urut'));
		$this->tgl_jt->setDbValue($rs->fields('tgl_jt'));
		$this->debet->setDbValue($rs->fields('debet'));
		$this->angs_pokok->setDbValue($rs->fields('angs_pokok'));
		$this->bunga->setDbValue($rs->fields('bunga'));
		$this->emp_id_auto->setDbValue($rs->fields('emp_id_auto'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->status->setDbValue($rs->fields('status'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_bayar->DbValue = $row['id_bayar'];
		$this->tgl_bayar->DbValue = $row['tgl_bayar'];
		$this->id_kredit->DbValue = $row['id_kredit'];
		$this->no_urut->DbValue = $row['no_urut'];
		$this->tgl_jt->DbValue = $row['tgl_jt'];
		$this->debet->DbValue = $row['debet'];
		$this->angs_pokok->DbValue = $row['angs_pokok'];
		$this->bunga->DbValue = $row['bunga'];
		$this->emp_id_auto->DbValue = $row['emp_id_auto'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->status->DbValue = $row['status'];
		$this->lastupdate_date->DbValue = $row['lastupdate_date'];
		$this->lastupdate_user->DbValue = $row['lastupdate_user'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_bayar")) <> "")
			$this->id_bayar->CurrentValue = $this->getKey("id_bayar"); // id_bayar
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
		if ($this->debet->FormValue == $this->debet->CurrentValue && is_numeric(ew_StrToFloat($this->debet->CurrentValue)))
			$this->debet->CurrentValue = ew_StrToFloat($this->debet->CurrentValue);

		// Convert decimal values if posted back
		if ($this->angs_pokok->FormValue == $this->angs_pokok->CurrentValue && is_numeric(ew_StrToFloat($this->angs_pokok->CurrentValue)))
			$this->angs_pokok->CurrentValue = ew_StrToFloat($this->angs_pokok->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bunga->FormValue == $this->bunga->CurrentValue && is_numeric(ew_StrToFloat($this->bunga->CurrentValue)))
			$this->bunga->CurrentValue = ew_StrToFloat($this->bunga->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_bayar
		// tgl_bayar
		// id_kredit
		// no_urut
		// tgl_jt
		// debet
		// angs_pokok
		// bunga
		// emp_id_auto
		// keterangan
		// status
		// lastupdate_date
		// lastupdate_user

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_bayar
		$this->id_bayar->ViewValue = $this->id_bayar->CurrentValue;
		$this->id_bayar->ViewCustomAttributes = "";

		// tgl_bayar
		$this->tgl_bayar->ViewValue = $this->tgl_bayar->CurrentValue;
		$this->tgl_bayar->ViewValue = ew_FormatDateTime($this->tgl_bayar->ViewValue, 0);
		$this->tgl_bayar->ViewCustomAttributes = "";

		// id_kredit
		$this->id_kredit->ViewValue = $this->id_kredit->CurrentValue;
		$this->id_kredit->ViewCustomAttributes = "";

		// no_urut
		$this->no_urut->ViewValue = $this->no_urut->CurrentValue;
		$this->no_urut->ViewCustomAttributes = "";

		// tgl_jt
		$this->tgl_jt->ViewValue = $this->tgl_jt->CurrentValue;
		$this->tgl_jt->ViewValue = ew_FormatDateTime($this->tgl_jt->ViewValue, 0);
		$this->tgl_jt->ViewCustomAttributes = "";

		// debet
		$this->debet->ViewValue = $this->debet->CurrentValue;
		$this->debet->ViewCustomAttributes = "";

		// angs_pokok
		$this->angs_pokok->ViewValue = $this->angs_pokok->CurrentValue;
		$this->angs_pokok->ViewCustomAttributes = "";

		// bunga
		$this->bunga->ViewValue = $this->bunga->CurrentValue;
		$this->bunga->ViewCustomAttributes = "";

		// emp_id_auto
		$this->emp_id_auto->ViewValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

			// id_bayar
			$this->id_bayar->LinkCustomAttributes = "";
			$this->id_bayar->HrefValue = "";
			$this->id_bayar->TooltipValue = "";

			// tgl_bayar
			$this->tgl_bayar->LinkCustomAttributes = "";
			$this->tgl_bayar->HrefValue = "";
			$this->tgl_bayar->TooltipValue = "";

			// id_kredit
			$this->id_kredit->LinkCustomAttributes = "";
			$this->id_kredit->HrefValue = "";
			$this->id_kredit->TooltipValue = "";

			// no_urut
			$this->no_urut->LinkCustomAttributes = "";
			$this->no_urut->HrefValue = "";
			$this->no_urut->TooltipValue = "";

			// tgl_jt
			$this->tgl_jt->LinkCustomAttributes = "";
			$this->tgl_jt->HrefValue = "";
			$this->tgl_jt->TooltipValue = "";

			// debet
			$this->debet->LinkCustomAttributes = "";
			$this->debet->HrefValue = "";
			$this->debet->TooltipValue = "";

			// angs_pokok
			$this->angs_pokok->LinkCustomAttributes = "";
			$this->angs_pokok->HrefValue = "";
			$this->angs_pokok->TooltipValue = "";

			// bunga
			$this->bunga->LinkCustomAttributes = "";
			$this->bunga->HrefValue = "";
			$this->bunga->TooltipValue = "";

			// emp_id_auto
			$this->emp_id_auto->LinkCustomAttributes = "";
			$this->emp_id_auto->HrefValue = "";
			$this->emp_id_auto->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

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
		$item->Body = "<button id=\"emf_zx_bayar_kredit\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_zx_bayar_kredit',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fzx_bayar_kreditlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($zx_bayar_kredit_list)) $zx_bayar_kredit_list = new czx_bayar_kredit_list();

// Page init
$zx_bayar_kredit_list->Page_Init();

// Page main
$zx_bayar_kredit_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$zx_bayar_kredit_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($zx_bayar_kredit->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fzx_bayar_kreditlist = new ew_Form("fzx_bayar_kreditlist", "list");
fzx_bayar_kreditlist.FormKeyCountName = '<?php echo $zx_bayar_kredit_list->FormKeyCountName ?>';

// Form_CustomValidate event
fzx_bayar_kreditlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fzx_bayar_kreditlist.ValidateRequired = true;
<?php } else { ?>
fzx_bayar_kreditlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fzx_bayar_kreditlistsrch = new ew_Form("fzx_bayar_kreditlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($zx_bayar_kredit->Export == "") { ?>
<div class="ewToolbar">
<?php if ($zx_bayar_kredit->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($zx_bayar_kredit_list->TotalRecs > 0 && $zx_bayar_kredit_list->ExportOptions->Visible()) { ?>
<?php $zx_bayar_kredit_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($zx_bayar_kredit_list->SearchOptions->Visible()) { ?>
<?php $zx_bayar_kredit_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($zx_bayar_kredit_list->FilterOptions->Visible()) { ?>
<?php $zx_bayar_kredit_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($zx_bayar_kredit->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $zx_bayar_kredit_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($zx_bayar_kredit_list->TotalRecs <= 0)
			$zx_bayar_kredit_list->TotalRecs = $zx_bayar_kredit->SelectRecordCount();
	} else {
		if (!$zx_bayar_kredit_list->Recordset && ($zx_bayar_kredit_list->Recordset = $zx_bayar_kredit_list->LoadRecordset()))
			$zx_bayar_kredit_list->TotalRecs = $zx_bayar_kredit_list->Recordset->RecordCount();
	}
	$zx_bayar_kredit_list->StartRec = 1;
	if ($zx_bayar_kredit_list->DisplayRecs <= 0 || ($zx_bayar_kredit->Export <> "" && $zx_bayar_kredit->ExportAll)) // Display all records
		$zx_bayar_kredit_list->DisplayRecs = $zx_bayar_kredit_list->TotalRecs;
	if (!($zx_bayar_kredit->Export <> "" && $zx_bayar_kredit->ExportAll))
		$zx_bayar_kredit_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$zx_bayar_kredit_list->Recordset = $zx_bayar_kredit_list->LoadRecordset($zx_bayar_kredit_list->StartRec-1, $zx_bayar_kredit_list->DisplayRecs);

	// Set no record found message
	if ($zx_bayar_kredit->CurrentAction == "" && $zx_bayar_kredit_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$zx_bayar_kredit_list->setWarningMessage(ew_DeniedMsg());
		if ($zx_bayar_kredit_list->SearchWhere == "0=101")
			$zx_bayar_kredit_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$zx_bayar_kredit_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$zx_bayar_kredit_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($zx_bayar_kredit->Export == "" && $zx_bayar_kredit->CurrentAction == "") { ?>
<form name="fzx_bayar_kreditlistsrch" id="fzx_bayar_kreditlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($zx_bayar_kredit_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fzx_bayar_kreditlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="zx_bayar_kredit">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($zx_bayar_kredit_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($zx_bayar_kredit_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $zx_bayar_kredit_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($zx_bayar_kredit_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($zx_bayar_kredit_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($zx_bayar_kredit_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($zx_bayar_kredit_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $zx_bayar_kredit_list->ShowPageHeader(); ?>
<?php
$zx_bayar_kredit_list->ShowMessage();
?>
<?php if ($zx_bayar_kredit_list->TotalRecs > 0 || $zx_bayar_kredit->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid zx_bayar_kredit">
<form name="fzx_bayar_kreditlist" id="fzx_bayar_kreditlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($zx_bayar_kredit_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $zx_bayar_kredit_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="zx_bayar_kredit">
<div id="gmp_zx_bayar_kredit" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($zx_bayar_kredit_list->TotalRecs > 0 || $zx_bayar_kredit->CurrentAction == "gridedit") { ?>
<table id="tbl_zx_bayar_kreditlist" class="table ewTable">
<?php echo $zx_bayar_kredit->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$zx_bayar_kredit_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$zx_bayar_kredit_list->RenderListOptions();

// Render list options (header, left)
$zx_bayar_kredit_list->ListOptions->Render("header", "left");
?>
<?php if ($zx_bayar_kredit->id_bayar->Visible) { // id_bayar ?>
	<?php if ($zx_bayar_kredit->SortUrl($zx_bayar_kredit->id_bayar) == "") { ?>
		<th data-name="id_bayar"><div id="elh_zx_bayar_kredit_id_bayar" class="zx_bayar_kredit_id_bayar"><div class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->id_bayar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_bayar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_bayar_kredit->SortUrl($zx_bayar_kredit->id_bayar) ?>',2);"><div id="elh_zx_bayar_kredit_id_bayar" class="zx_bayar_kredit_id_bayar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->id_bayar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_bayar_kredit->id_bayar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_bayar_kredit->id_bayar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_bayar_kredit->tgl_bayar->Visible) { // tgl_bayar ?>
	<?php if ($zx_bayar_kredit->SortUrl($zx_bayar_kredit->tgl_bayar) == "") { ?>
		<th data-name="tgl_bayar"><div id="elh_zx_bayar_kredit_tgl_bayar" class="zx_bayar_kredit_tgl_bayar"><div class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->tgl_bayar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_bayar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_bayar_kredit->SortUrl($zx_bayar_kredit->tgl_bayar) ?>',2);"><div id="elh_zx_bayar_kredit_tgl_bayar" class="zx_bayar_kredit_tgl_bayar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->tgl_bayar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_bayar_kredit->tgl_bayar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_bayar_kredit->tgl_bayar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_bayar_kredit->id_kredit->Visible) { // id_kredit ?>
	<?php if ($zx_bayar_kredit->SortUrl($zx_bayar_kredit->id_kredit) == "") { ?>
		<th data-name="id_kredit"><div id="elh_zx_bayar_kredit_id_kredit" class="zx_bayar_kredit_id_kredit"><div class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->id_kredit->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_kredit"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_bayar_kredit->SortUrl($zx_bayar_kredit->id_kredit) ?>',2);"><div id="elh_zx_bayar_kredit_id_kredit" class="zx_bayar_kredit_id_kredit">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->id_kredit->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_bayar_kredit->id_kredit->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_bayar_kredit->id_kredit->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_bayar_kredit->no_urut->Visible) { // no_urut ?>
	<?php if ($zx_bayar_kredit->SortUrl($zx_bayar_kredit->no_urut) == "") { ?>
		<th data-name="no_urut"><div id="elh_zx_bayar_kredit_no_urut" class="zx_bayar_kredit_no_urut"><div class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->no_urut->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="no_urut"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_bayar_kredit->SortUrl($zx_bayar_kredit->no_urut) ?>',2);"><div id="elh_zx_bayar_kredit_no_urut" class="zx_bayar_kredit_no_urut">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->no_urut->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_bayar_kredit->no_urut->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_bayar_kredit->no_urut->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_bayar_kredit->tgl_jt->Visible) { // tgl_jt ?>
	<?php if ($zx_bayar_kredit->SortUrl($zx_bayar_kredit->tgl_jt) == "") { ?>
		<th data-name="tgl_jt"><div id="elh_zx_bayar_kredit_tgl_jt" class="zx_bayar_kredit_tgl_jt"><div class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->tgl_jt->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_jt"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_bayar_kredit->SortUrl($zx_bayar_kredit->tgl_jt) ?>',2);"><div id="elh_zx_bayar_kredit_tgl_jt" class="zx_bayar_kredit_tgl_jt">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->tgl_jt->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_bayar_kredit->tgl_jt->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_bayar_kredit->tgl_jt->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_bayar_kredit->debet->Visible) { // debet ?>
	<?php if ($zx_bayar_kredit->SortUrl($zx_bayar_kredit->debet) == "") { ?>
		<th data-name="debet"><div id="elh_zx_bayar_kredit_debet" class="zx_bayar_kredit_debet"><div class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->debet->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_bayar_kredit->SortUrl($zx_bayar_kredit->debet) ?>',2);"><div id="elh_zx_bayar_kredit_debet" class="zx_bayar_kredit_debet">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->debet->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_bayar_kredit->debet->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_bayar_kredit->debet->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_bayar_kredit->angs_pokok->Visible) { // angs_pokok ?>
	<?php if ($zx_bayar_kredit->SortUrl($zx_bayar_kredit->angs_pokok) == "") { ?>
		<th data-name="angs_pokok"><div id="elh_zx_bayar_kredit_angs_pokok" class="zx_bayar_kredit_angs_pokok"><div class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->angs_pokok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angs_pokok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_bayar_kredit->SortUrl($zx_bayar_kredit->angs_pokok) ?>',2);"><div id="elh_zx_bayar_kredit_angs_pokok" class="zx_bayar_kredit_angs_pokok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->angs_pokok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_bayar_kredit->angs_pokok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_bayar_kredit->angs_pokok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_bayar_kredit->bunga->Visible) { // bunga ?>
	<?php if ($zx_bayar_kredit->SortUrl($zx_bayar_kredit->bunga) == "") { ?>
		<th data-name="bunga"><div id="elh_zx_bayar_kredit_bunga" class="zx_bayar_kredit_bunga"><div class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->bunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_bayar_kredit->SortUrl($zx_bayar_kredit->bunga) ?>',2);"><div id="elh_zx_bayar_kredit_bunga" class="zx_bayar_kredit_bunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->bunga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_bayar_kredit->bunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_bayar_kredit->bunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_bayar_kredit->emp_id_auto->Visible) { // emp_id_auto ?>
	<?php if ($zx_bayar_kredit->SortUrl($zx_bayar_kredit->emp_id_auto) == "") { ?>
		<th data-name="emp_id_auto"><div id="elh_zx_bayar_kredit_emp_id_auto" class="zx_bayar_kredit_emp_id_auto"><div class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->emp_id_auto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="emp_id_auto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_bayar_kredit->SortUrl($zx_bayar_kredit->emp_id_auto) ?>',2);"><div id="elh_zx_bayar_kredit_emp_id_auto" class="zx_bayar_kredit_emp_id_auto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->emp_id_auto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_bayar_kredit->emp_id_auto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_bayar_kredit->emp_id_auto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_bayar_kredit->status->Visible) { // status ?>
	<?php if ($zx_bayar_kredit->SortUrl($zx_bayar_kredit->status) == "") { ?>
		<th data-name="status"><div id="elh_zx_bayar_kredit_status" class="zx_bayar_kredit_status"><div class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_bayar_kredit->SortUrl($zx_bayar_kredit->status) ?>',2);"><div id="elh_zx_bayar_kredit_status" class="zx_bayar_kredit_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_bayar_kredit->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_bayar_kredit->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_bayar_kredit->lastupdate_date->Visible) { // lastupdate_date ?>
	<?php if ($zx_bayar_kredit->SortUrl($zx_bayar_kredit->lastupdate_date) == "") { ?>
		<th data-name="lastupdate_date"><div id="elh_zx_bayar_kredit_lastupdate_date" class="zx_bayar_kredit_lastupdate_date"><div class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->lastupdate_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lastupdate_date"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_bayar_kredit->SortUrl($zx_bayar_kredit->lastupdate_date) ?>',2);"><div id="elh_zx_bayar_kredit_lastupdate_date" class="zx_bayar_kredit_lastupdate_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->lastupdate_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zx_bayar_kredit->lastupdate_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_bayar_kredit->lastupdate_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zx_bayar_kredit->lastupdate_user->Visible) { // lastupdate_user ?>
	<?php if ($zx_bayar_kredit->SortUrl($zx_bayar_kredit->lastupdate_user) == "") { ?>
		<th data-name="lastupdate_user"><div id="elh_zx_bayar_kredit_lastupdate_user" class="zx_bayar_kredit_lastupdate_user"><div class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->lastupdate_user->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lastupdate_user"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zx_bayar_kredit->SortUrl($zx_bayar_kredit->lastupdate_user) ?>',2);"><div id="elh_zx_bayar_kredit_lastupdate_user" class="zx_bayar_kredit_lastupdate_user">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zx_bayar_kredit->lastupdate_user->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($zx_bayar_kredit->lastupdate_user->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zx_bayar_kredit->lastupdate_user->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$zx_bayar_kredit_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($zx_bayar_kredit->ExportAll && $zx_bayar_kredit->Export <> "") {
	$zx_bayar_kredit_list->StopRec = $zx_bayar_kredit_list->TotalRecs;
} else {

	// Set the last record to display
	if ($zx_bayar_kredit_list->TotalRecs > $zx_bayar_kredit_list->StartRec + $zx_bayar_kredit_list->DisplayRecs - 1)
		$zx_bayar_kredit_list->StopRec = $zx_bayar_kredit_list->StartRec + $zx_bayar_kredit_list->DisplayRecs - 1;
	else
		$zx_bayar_kredit_list->StopRec = $zx_bayar_kredit_list->TotalRecs;
}
$zx_bayar_kredit_list->RecCnt = $zx_bayar_kredit_list->StartRec - 1;
if ($zx_bayar_kredit_list->Recordset && !$zx_bayar_kredit_list->Recordset->EOF) {
	$zx_bayar_kredit_list->Recordset->MoveFirst();
	$bSelectLimit = $zx_bayar_kredit_list->UseSelectLimit;
	if (!$bSelectLimit && $zx_bayar_kredit_list->StartRec > 1)
		$zx_bayar_kredit_list->Recordset->Move($zx_bayar_kredit_list->StartRec - 1);
} elseif (!$zx_bayar_kredit->AllowAddDeleteRow && $zx_bayar_kredit_list->StopRec == 0) {
	$zx_bayar_kredit_list->StopRec = $zx_bayar_kredit->GridAddRowCount;
}

// Initialize aggregate
$zx_bayar_kredit->RowType = EW_ROWTYPE_AGGREGATEINIT;
$zx_bayar_kredit->ResetAttrs();
$zx_bayar_kredit_list->RenderRow();
while ($zx_bayar_kredit_list->RecCnt < $zx_bayar_kredit_list->StopRec) {
	$zx_bayar_kredit_list->RecCnt++;
	if (intval($zx_bayar_kredit_list->RecCnt) >= intval($zx_bayar_kredit_list->StartRec)) {
		$zx_bayar_kredit_list->RowCnt++;

		// Set up key count
		$zx_bayar_kredit_list->KeyCount = $zx_bayar_kredit_list->RowIndex;

		// Init row class and style
		$zx_bayar_kredit->ResetAttrs();
		$zx_bayar_kredit->CssClass = "";
		if ($zx_bayar_kredit->CurrentAction == "gridadd") {
		} else {
			$zx_bayar_kredit_list->LoadRowValues($zx_bayar_kredit_list->Recordset); // Load row values
		}
		$zx_bayar_kredit->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$zx_bayar_kredit->RowAttrs = array_merge($zx_bayar_kredit->RowAttrs, array('data-rowindex'=>$zx_bayar_kredit_list->RowCnt, 'id'=>'r' . $zx_bayar_kredit_list->RowCnt . '_zx_bayar_kredit', 'data-rowtype'=>$zx_bayar_kredit->RowType));

		// Render row
		$zx_bayar_kredit_list->RenderRow();

		// Render list options
		$zx_bayar_kredit_list->RenderListOptions();
?>
	<tr<?php echo $zx_bayar_kredit->RowAttributes() ?>>
<?php

// Render list options (body, left)
$zx_bayar_kredit_list->ListOptions->Render("body", "left", $zx_bayar_kredit_list->RowCnt);
?>
	<?php if ($zx_bayar_kredit->id_bayar->Visible) { // id_bayar ?>
		<td data-name="id_bayar"<?php echo $zx_bayar_kredit->id_bayar->CellAttributes() ?>>
<span id="el<?php echo $zx_bayar_kredit_list->RowCnt ?>_zx_bayar_kredit_id_bayar" class="zx_bayar_kredit_id_bayar">
<span<?php echo $zx_bayar_kredit->id_bayar->ViewAttributes() ?>>
<?php echo $zx_bayar_kredit->id_bayar->ListViewValue() ?></span>
</span>
<a id="<?php echo $zx_bayar_kredit_list->PageObjName . "_row_" . $zx_bayar_kredit_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($zx_bayar_kredit->tgl_bayar->Visible) { // tgl_bayar ?>
		<td data-name="tgl_bayar"<?php echo $zx_bayar_kredit->tgl_bayar->CellAttributes() ?>>
<span id="el<?php echo $zx_bayar_kredit_list->RowCnt ?>_zx_bayar_kredit_tgl_bayar" class="zx_bayar_kredit_tgl_bayar">
<span<?php echo $zx_bayar_kredit->tgl_bayar->ViewAttributes() ?>>
<?php echo $zx_bayar_kredit->tgl_bayar->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_bayar_kredit->id_kredit->Visible) { // id_kredit ?>
		<td data-name="id_kredit"<?php echo $zx_bayar_kredit->id_kredit->CellAttributes() ?>>
<span id="el<?php echo $zx_bayar_kredit_list->RowCnt ?>_zx_bayar_kredit_id_kredit" class="zx_bayar_kredit_id_kredit">
<span<?php echo $zx_bayar_kredit->id_kredit->ViewAttributes() ?>>
<?php echo $zx_bayar_kredit->id_kredit->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_bayar_kredit->no_urut->Visible) { // no_urut ?>
		<td data-name="no_urut"<?php echo $zx_bayar_kredit->no_urut->CellAttributes() ?>>
<span id="el<?php echo $zx_bayar_kredit_list->RowCnt ?>_zx_bayar_kredit_no_urut" class="zx_bayar_kredit_no_urut">
<span<?php echo $zx_bayar_kredit->no_urut->ViewAttributes() ?>>
<?php echo $zx_bayar_kredit->no_urut->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_bayar_kredit->tgl_jt->Visible) { // tgl_jt ?>
		<td data-name="tgl_jt"<?php echo $zx_bayar_kredit->tgl_jt->CellAttributes() ?>>
<span id="el<?php echo $zx_bayar_kredit_list->RowCnt ?>_zx_bayar_kredit_tgl_jt" class="zx_bayar_kredit_tgl_jt">
<span<?php echo $zx_bayar_kredit->tgl_jt->ViewAttributes() ?>>
<?php echo $zx_bayar_kredit->tgl_jt->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_bayar_kredit->debet->Visible) { // debet ?>
		<td data-name="debet"<?php echo $zx_bayar_kredit->debet->CellAttributes() ?>>
<span id="el<?php echo $zx_bayar_kredit_list->RowCnt ?>_zx_bayar_kredit_debet" class="zx_bayar_kredit_debet">
<span<?php echo $zx_bayar_kredit->debet->ViewAttributes() ?>>
<?php echo $zx_bayar_kredit->debet->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_bayar_kredit->angs_pokok->Visible) { // angs_pokok ?>
		<td data-name="angs_pokok"<?php echo $zx_bayar_kredit->angs_pokok->CellAttributes() ?>>
<span id="el<?php echo $zx_bayar_kredit_list->RowCnt ?>_zx_bayar_kredit_angs_pokok" class="zx_bayar_kredit_angs_pokok">
<span<?php echo $zx_bayar_kredit->angs_pokok->ViewAttributes() ?>>
<?php echo $zx_bayar_kredit->angs_pokok->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_bayar_kredit->bunga->Visible) { // bunga ?>
		<td data-name="bunga"<?php echo $zx_bayar_kredit->bunga->CellAttributes() ?>>
<span id="el<?php echo $zx_bayar_kredit_list->RowCnt ?>_zx_bayar_kredit_bunga" class="zx_bayar_kredit_bunga">
<span<?php echo $zx_bayar_kredit->bunga->ViewAttributes() ?>>
<?php echo $zx_bayar_kredit->bunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_bayar_kredit->emp_id_auto->Visible) { // emp_id_auto ?>
		<td data-name="emp_id_auto"<?php echo $zx_bayar_kredit->emp_id_auto->CellAttributes() ?>>
<span id="el<?php echo $zx_bayar_kredit_list->RowCnt ?>_zx_bayar_kredit_emp_id_auto" class="zx_bayar_kredit_emp_id_auto">
<span<?php echo $zx_bayar_kredit->emp_id_auto->ViewAttributes() ?>>
<?php echo $zx_bayar_kredit->emp_id_auto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_bayar_kredit->status->Visible) { // status ?>
		<td data-name="status"<?php echo $zx_bayar_kredit->status->CellAttributes() ?>>
<span id="el<?php echo $zx_bayar_kredit_list->RowCnt ?>_zx_bayar_kredit_status" class="zx_bayar_kredit_status">
<span<?php echo $zx_bayar_kredit->status->ViewAttributes() ?>>
<?php echo $zx_bayar_kredit->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_bayar_kredit->lastupdate_date->Visible) { // lastupdate_date ?>
		<td data-name="lastupdate_date"<?php echo $zx_bayar_kredit->lastupdate_date->CellAttributes() ?>>
<span id="el<?php echo $zx_bayar_kredit_list->RowCnt ?>_zx_bayar_kredit_lastupdate_date" class="zx_bayar_kredit_lastupdate_date">
<span<?php echo $zx_bayar_kredit->lastupdate_date->ViewAttributes() ?>>
<?php echo $zx_bayar_kredit->lastupdate_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($zx_bayar_kredit->lastupdate_user->Visible) { // lastupdate_user ?>
		<td data-name="lastupdate_user"<?php echo $zx_bayar_kredit->lastupdate_user->CellAttributes() ?>>
<span id="el<?php echo $zx_bayar_kredit_list->RowCnt ?>_zx_bayar_kredit_lastupdate_user" class="zx_bayar_kredit_lastupdate_user">
<span<?php echo $zx_bayar_kredit->lastupdate_user->ViewAttributes() ?>>
<?php echo $zx_bayar_kredit->lastupdate_user->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$zx_bayar_kredit_list->ListOptions->Render("body", "right", $zx_bayar_kredit_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($zx_bayar_kredit->CurrentAction <> "gridadd")
		$zx_bayar_kredit_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($zx_bayar_kredit->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($zx_bayar_kredit_list->Recordset)
	$zx_bayar_kredit_list->Recordset->Close();
?>
<?php if ($zx_bayar_kredit->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($zx_bayar_kredit->CurrentAction <> "gridadd" && $zx_bayar_kredit->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($zx_bayar_kredit_list->Pager)) $zx_bayar_kredit_list->Pager = new cPrevNextPager($zx_bayar_kredit_list->StartRec, $zx_bayar_kredit_list->DisplayRecs, $zx_bayar_kredit_list->TotalRecs) ?>
<?php if ($zx_bayar_kredit_list->Pager->RecordCount > 0 && $zx_bayar_kredit_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($zx_bayar_kredit_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $zx_bayar_kredit_list->PageUrl() ?>start=<?php echo $zx_bayar_kredit_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($zx_bayar_kredit_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $zx_bayar_kredit_list->PageUrl() ?>start=<?php echo $zx_bayar_kredit_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $zx_bayar_kredit_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($zx_bayar_kredit_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $zx_bayar_kredit_list->PageUrl() ?>start=<?php echo $zx_bayar_kredit_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($zx_bayar_kredit_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $zx_bayar_kredit_list->PageUrl() ?>start=<?php echo $zx_bayar_kredit_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $zx_bayar_kredit_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $zx_bayar_kredit_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $zx_bayar_kredit_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $zx_bayar_kredit_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($zx_bayar_kredit_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($zx_bayar_kredit_list->TotalRecs == 0 && $zx_bayar_kredit->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($zx_bayar_kredit_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($zx_bayar_kredit->Export == "") { ?>
<script type="text/javascript">
fzx_bayar_kreditlistsrch.FilterList = <?php echo $zx_bayar_kredit_list->GetFilterList() ?>;
fzx_bayar_kreditlistsrch.Init();
fzx_bayar_kreditlist.Init();
</script>
<?php } ?>
<?php
$zx_bayar_kredit_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($zx_bayar_kredit->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$zx_bayar_kredit_list->Page_Terminate();
?>
