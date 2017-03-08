<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "deviceinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$device_list = NULL; // Initialize page object first

class cdevice_list extends cdevice {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'device';

	// Page object name
	var $PageObjName = 'device_list';

	// Grid form hidden field names
	var $FormName = 'fdevicelist';
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

		// Table object (device)
		if (!isset($GLOBALS["device"]) || get_class($GLOBALS["device"]) == "cdevice") {
			$GLOBALS["device"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["device"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "deviceadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "devicedelete.php";
		$this->MultiUpdateUrl = "deviceupdate.php";

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'device', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fdevicelistsrch";

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
		$this->sn->SetVisibility();
		$this->activation_code->SetVisibility();
		$this->act_code_realtime->SetVisibility();
		$this->device_name->SetVisibility();
		$this->comm_key->SetVisibility();
		$this->dev_id->SetVisibility();
		$this->comm_type->SetVisibility();
		$this->ip_address->SetVisibility();
		$this->id_type->SetVisibility();
		$this->dev_type->SetVisibility();
		$this->serial_port->SetVisibility();
		$this->baud_rate->SetVisibility();
		$this->ethernet_port->SetVisibility();
		$this->layar->SetVisibility();
		$this->alg_ver->SetVisibility();
		$this->use_realtime->SetVisibility();
		$this->group_realtime->SetVisibility();
		$this->last_download->SetVisibility();
		$this->ATTLOGStamp->SetVisibility();
		$this->OPERLOGStamp->SetVisibility();
		$this->ATTPHOTOStamp->SetVisibility();

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
		global $EW_EXPORT, $device;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($device);
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
			$this->sn->setFormValue($arrKeyFlds[0]);
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fdevicelistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->sn->AdvancedSearch->ToJSON(), ","); // Field sn
		$sFilterList = ew_Concat($sFilterList, $this->activation_code->AdvancedSearch->ToJSON(), ","); // Field activation_code
		$sFilterList = ew_Concat($sFilterList, $this->act_code_realtime->AdvancedSearch->ToJSON(), ","); // Field act_code_realtime
		$sFilterList = ew_Concat($sFilterList, $this->device_name->AdvancedSearch->ToJSON(), ","); // Field device_name
		$sFilterList = ew_Concat($sFilterList, $this->comm_key->AdvancedSearch->ToJSON(), ","); // Field comm_key
		$sFilterList = ew_Concat($sFilterList, $this->dev_id->AdvancedSearch->ToJSON(), ","); // Field dev_id
		$sFilterList = ew_Concat($sFilterList, $this->comm_type->AdvancedSearch->ToJSON(), ","); // Field comm_type
		$sFilterList = ew_Concat($sFilterList, $this->ip_address->AdvancedSearch->ToJSON(), ","); // Field ip_address
		$sFilterList = ew_Concat($sFilterList, $this->id_type->AdvancedSearch->ToJSON(), ","); // Field id_type
		$sFilterList = ew_Concat($sFilterList, $this->dev_type->AdvancedSearch->ToJSON(), ","); // Field dev_type
		$sFilterList = ew_Concat($sFilterList, $this->serial_port->AdvancedSearch->ToJSON(), ","); // Field serial_port
		$sFilterList = ew_Concat($sFilterList, $this->baud_rate->AdvancedSearch->ToJSON(), ","); // Field baud_rate
		$sFilterList = ew_Concat($sFilterList, $this->ethernet_port->AdvancedSearch->ToJSON(), ","); // Field ethernet_port
		$sFilterList = ew_Concat($sFilterList, $this->layar->AdvancedSearch->ToJSON(), ","); // Field layar
		$sFilterList = ew_Concat($sFilterList, $this->alg_ver->AdvancedSearch->ToJSON(), ","); // Field alg_ver
		$sFilterList = ew_Concat($sFilterList, $this->use_realtime->AdvancedSearch->ToJSON(), ","); // Field use_realtime
		$sFilterList = ew_Concat($sFilterList, $this->group_realtime->AdvancedSearch->ToJSON(), ","); // Field group_realtime
		$sFilterList = ew_Concat($sFilterList, $this->last_download->AdvancedSearch->ToJSON(), ","); // Field last_download
		$sFilterList = ew_Concat($sFilterList, $this->ATTLOGStamp->AdvancedSearch->ToJSON(), ","); // Field ATTLOGStamp
		$sFilterList = ew_Concat($sFilterList, $this->OPERLOGStamp->AdvancedSearch->ToJSON(), ","); // Field OPERLOGStamp
		$sFilterList = ew_Concat($sFilterList, $this->ATTPHOTOStamp->AdvancedSearch->ToJSON(), ","); // Field ATTPHOTOStamp
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fdevicelistsrch", $filters);

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

		// Field sn
		$this->sn->AdvancedSearch->SearchValue = @$filter["x_sn"];
		$this->sn->AdvancedSearch->SearchOperator = @$filter["z_sn"];
		$this->sn->AdvancedSearch->SearchCondition = @$filter["v_sn"];
		$this->sn->AdvancedSearch->SearchValue2 = @$filter["y_sn"];
		$this->sn->AdvancedSearch->SearchOperator2 = @$filter["w_sn"];
		$this->sn->AdvancedSearch->Save();

		// Field activation_code
		$this->activation_code->AdvancedSearch->SearchValue = @$filter["x_activation_code"];
		$this->activation_code->AdvancedSearch->SearchOperator = @$filter["z_activation_code"];
		$this->activation_code->AdvancedSearch->SearchCondition = @$filter["v_activation_code"];
		$this->activation_code->AdvancedSearch->SearchValue2 = @$filter["y_activation_code"];
		$this->activation_code->AdvancedSearch->SearchOperator2 = @$filter["w_activation_code"];
		$this->activation_code->AdvancedSearch->Save();

		// Field act_code_realtime
		$this->act_code_realtime->AdvancedSearch->SearchValue = @$filter["x_act_code_realtime"];
		$this->act_code_realtime->AdvancedSearch->SearchOperator = @$filter["z_act_code_realtime"];
		$this->act_code_realtime->AdvancedSearch->SearchCondition = @$filter["v_act_code_realtime"];
		$this->act_code_realtime->AdvancedSearch->SearchValue2 = @$filter["y_act_code_realtime"];
		$this->act_code_realtime->AdvancedSearch->SearchOperator2 = @$filter["w_act_code_realtime"];
		$this->act_code_realtime->AdvancedSearch->Save();

		// Field device_name
		$this->device_name->AdvancedSearch->SearchValue = @$filter["x_device_name"];
		$this->device_name->AdvancedSearch->SearchOperator = @$filter["z_device_name"];
		$this->device_name->AdvancedSearch->SearchCondition = @$filter["v_device_name"];
		$this->device_name->AdvancedSearch->SearchValue2 = @$filter["y_device_name"];
		$this->device_name->AdvancedSearch->SearchOperator2 = @$filter["w_device_name"];
		$this->device_name->AdvancedSearch->Save();

		// Field comm_key
		$this->comm_key->AdvancedSearch->SearchValue = @$filter["x_comm_key"];
		$this->comm_key->AdvancedSearch->SearchOperator = @$filter["z_comm_key"];
		$this->comm_key->AdvancedSearch->SearchCondition = @$filter["v_comm_key"];
		$this->comm_key->AdvancedSearch->SearchValue2 = @$filter["y_comm_key"];
		$this->comm_key->AdvancedSearch->SearchOperator2 = @$filter["w_comm_key"];
		$this->comm_key->AdvancedSearch->Save();

		// Field dev_id
		$this->dev_id->AdvancedSearch->SearchValue = @$filter["x_dev_id"];
		$this->dev_id->AdvancedSearch->SearchOperator = @$filter["z_dev_id"];
		$this->dev_id->AdvancedSearch->SearchCondition = @$filter["v_dev_id"];
		$this->dev_id->AdvancedSearch->SearchValue2 = @$filter["y_dev_id"];
		$this->dev_id->AdvancedSearch->SearchOperator2 = @$filter["w_dev_id"];
		$this->dev_id->AdvancedSearch->Save();

		// Field comm_type
		$this->comm_type->AdvancedSearch->SearchValue = @$filter["x_comm_type"];
		$this->comm_type->AdvancedSearch->SearchOperator = @$filter["z_comm_type"];
		$this->comm_type->AdvancedSearch->SearchCondition = @$filter["v_comm_type"];
		$this->comm_type->AdvancedSearch->SearchValue2 = @$filter["y_comm_type"];
		$this->comm_type->AdvancedSearch->SearchOperator2 = @$filter["w_comm_type"];
		$this->comm_type->AdvancedSearch->Save();

		// Field ip_address
		$this->ip_address->AdvancedSearch->SearchValue = @$filter["x_ip_address"];
		$this->ip_address->AdvancedSearch->SearchOperator = @$filter["z_ip_address"];
		$this->ip_address->AdvancedSearch->SearchCondition = @$filter["v_ip_address"];
		$this->ip_address->AdvancedSearch->SearchValue2 = @$filter["y_ip_address"];
		$this->ip_address->AdvancedSearch->SearchOperator2 = @$filter["w_ip_address"];
		$this->ip_address->AdvancedSearch->Save();

		// Field id_type
		$this->id_type->AdvancedSearch->SearchValue = @$filter["x_id_type"];
		$this->id_type->AdvancedSearch->SearchOperator = @$filter["z_id_type"];
		$this->id_type->AdvancedSearch->SearchCondition = @$filter["v_id_type"];
		$this->id_type->AdvancedSearch->SearchValue2 = @$filter["y_id_type"];
		$this->id_type->AdvancedSearch->SearchOperator2 = @$filter["w_id_type"];
		$this->id_type->AdvancedSearch->Save();

		// Field dev_type
		$this->dev_type->AdvancedSearch->SearchValue = @$filter["x_dev_type"];
		$this->dev_type->AdvancedSearch->SearchOperator = @$filter["z_dev_type"];
		$this->dev_type->AdvancedSearch->SearchCondition = @$filter["v_dev_type"];
		$this->dev_type->AdvancedSearch->SearchValue2 = @$filter["y_dev_type"];
		$this->dev_type->AdvancedSearch->SearchOperator2 = @$filter["w_dev_type"];
		$this->dev_type->AdvancedSearch->Save();

		// Field serial_port
		$this->serial_port->AdvancedSearch->SearchValue = @$filter["x_serial_port"];
		$this->serial_port->AdvancedSearch->SearchOperator = @$filter["z_serial_port"];
		$this->serial_port->AdvancedSearch->SearchCondition = @$filter["v_serial_port"];
		$this->serial_port->AdvancedSearch->SearchValue2 = @$filter["y_serial_port"];
		$this->serial_port->AdvancedSearch->SearchOperator2 = @$filter["w_serial_port"];
		$this->serial_port->AdvancedSearch->Save();

		// Field baud_rate
		$this->baud_rate->AdvancedSearch->SearchValue = @$filter["x_baud_rate"];
		$this->baud_rate->AdvancedSearch->SearchOperator = @$filter["z_baud_rate"];
		$this->baud_rate->AdvancedSearch->SearchCondition = @$filter["v_baud_rate"];
		$this->baud_rate->AdvancedSearch->SearchValue2 = @$filter["y_baud_rate"];
		$this->baud_rate->AdvancedSearch->SearchOperator2 = @$filter["w_baud_rate"];
		$this->baud_rate->AdvancedSearch->Save();

		// Field ethernet_port
		$this->ethernet_port->AdvancedSearch->SearchValue = @$filter["x_ethernet_port"];
		$this->ethernet_port->AdvancedSearch->SearchOperator = @$filter["z_ethernet_port"];
		$this->ethernet_port->AdvancedSearch->SearchCondition = @$filter["v_ethernet_port"];
		$this->ethernet_port->AdvancedSearch->SearchValue2 = @$filter["y_ethernet_port"];
		$this->ethernet_port->AdvancedSearch->SearchOperator2 = @$filter["w_ethernet_port"];
		$this->ethernet_port->AdvancedSearch->Save();

		// Field layar
		$this->layar->AdvancedSearch->SearchValue = @$filter["x_layar"];
		$this->layar->AdvancedSearch->SearchOperator = @$filter["z_layar"];
		$this->layar->AdvancedSearch->SearchCondition = @$filter["v_layar"];
		$this->layar->AdvancedSearch->SearchValue2 = @$filter["y_layar"];
		$this->layar->AdvancedSearch->SearchOperator2 = @$filter["w_layar"];
		$this->layar->AdvancedSearch->Save();

		// Field alg_ver
		$this->alg_ver->AdvancedSearch->SearchValue = @$filter["x_alg_ver"];
		$this->alg_ver->AdvancedSearch->SearchOperator = @$filter["z_alg_ver"];
		$this->alg_ver->AdvancedSearch->SearchCondition = @$filter["v_alg_ver"];
		$this->alg_ver->AdvancedSearch->SearchValue2 = @$filter["y_alg_ver"];
		$this->alg_ver->AdvancedSearch->SearchOperator2 = @$filter["w_alg_ver"];
		$this->alg_ver->AdvancedSearch->Save();

		// Field use_realtime
		$this->use_realtime->AdvancedSearch->SearchValue = @$filter["x_use_realtime"];
		$this->use_realtime->AdvancedSearch->SearchOperator = @$filter["z_use_realtime"];
		$this->use_realtime->AdvancedSearch->SearchCondition = @$filter["v_use_realtime"];
		$this->use_realtime->AdvancedSearch->SearchValue2 = @$filter["y_use_realtime"];
		$this->use_realtime->AdvancedSearch->SearchOperator2 = @$filter["w_use_realtime"];
		$this->use_realtime->AdvancedSearch->Save();

		// Field group_realtime
		$this->group_realtime->AdvancedSearch->SearchValue = @$filter["x_group_realtime"];
		$this->group_realtime->AdvancedSearch->SearchOperator = @$filter["z_group_realtime"];
		$this->group_realtime->AdvancedSearch->SearchCondition = @$filter["v_group_realtime"];
		$this->group_realtime->AdvancedSearch->SearchValue2 = @$filter["y_group_realtime"];
		$this->group_realtime->AdvancedSearch->SearchOperator2 = @$filter["w_group_realtime"];
		$this->group_realtime->AdvancedSearch->Save();

		// Field last_download
		$this->last_download->AdvancedSearch->SearchValue = @$filter["x_last_download"];
		$this->last_download->AdvancedSearch->SearchOperator = @$filter["z_last_download"];
		$this->last_download->AdvancedSearch->SearchCondition = @$filter["v_last_download"];
		$this->last_download->AdvancedSearch->SearchValue2 = @$filter["y_last_download"];
		$this->last_download->AdvancedSearch->SearchOperator2 = @$filter["w_last_download"];
		$this->last_download->AdvancedSearch->Save();

		// Field ATTLOGStamp
		$this->ATTLOGStamp->AdvancedSearch->SearchValue = @$filter["x_ATTLOGStamp"];
		$this->ATTLOGStamp->AdvancedSearch->SearchOperator = @$filter["z_ATTLOGStamp"];
		$this->ATTLOGStamp->AdvancedSearch->SearchCondition = @$filter["v_ATTLOGStamp"];
		$this->ATTLOGStamp->AdvancedSearch->SearchValue2 = @$filter["y_ATTLOGStamp"];
		$this->ATTLOGStamp->AdvancedSearch->SearchOperator2 = @$filter["w_ATTLOGStamp"];
		$this->ATTLOGStamp->AdvancedSearch->Save();

		// Field OPERLOGStamp
		$this->OPERLOGStamp->AdvancedSearch->SearchValue = @$filter["x_OPERLOGStamp"];
		$this->OPERLOGStamp->AdvancedSearch->SearchOperator = @$filter["z_OPERLOGStamp"];
		$this->OPERLOGStamp->AdvancedSearch->SearchCondition = @$filter["v_OPERLOGStamp"];
		$this->OPERLOGStamp->AdvancedSearch->SearchValue2 = @$filter["y_OPERLOGStamp"];
		$this->OPERLOGStamp->AdvancedSearch->SearchOperator2 = @$filter["w_OPERLOGStamp"];
		$this->OPERLOGStamp->AdvancedSearch->Save();

		// Field ATTPHOTOStamp
		$this->ATTPHOTOStamp->AdvancedSearch->SearchValue = @$filter["x_ATTPHOTOStamp"];
		$this->ATTPHOTOStamp->AdvancedSearch->SearchOperator = @$filter["z_ATTPHOTOStamp"];
		$this->ATTPHOTOStamp->AdvancedSearch->SearchCondition = @$filter["v_ATTPHOTOStamp"];
		$this->ATTPHOTOStamp->AdvancedSearch->SearchValue2 = @$filter["y_ATTPHOTOStamp"];
		$this->ATTPHOTOStamp->AdvancedSearch->SearchOperator2 = @$filter["w_ATTPHOTOStamp"];
		$this->ATTPHOTOStamp->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->sn, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activation_code, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->act_code_realtime, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->device_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ip_address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->serial_port, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->baud_rate, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ethernet_port, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ATTLOGStamp, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->OPERLOGStamp, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ATTPHOTOStamp, $arKeywords, $type);
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
			$this->UpdateSort($this->sn, $bCtrl); // sn
			$this->UpdateSort($this->activation_code, $bCtrl); // activation_code
			$this->UpdateSort($this->act_code_realtime, $bCtrl); // act_code_realtime
			$this->UpdateSort($this->device_name, $bCtrl); // device_name
			$this->UpdateSort($this->comm_key, $bCtrl); // comm_key
			$this->UpdateSort($this->dev_id, $bCtrl); // dev_id
			$this->UpdateSort($this->comm_type, $bCtrl); // comm_type
			$this->UpdateSort($this->ip_address, $bCtrl); // ip_address
			$this->UpdateSort($this->id_type, $bCtrl); // id_type
			$this->UpdateSort($this->dev_type, $bCtrl); // dev_type
			$this->UpdateSort($this->serial_port, $bCtrl); // serial_port
			$this->UpdateSort($this->baud_rate, $bCtrl); // baud_rate
			$this->UpdateSort($this->ethernet_port, $bCtrl); // ethernet_port
			$this->UpdateSort($this->layar, $bCtrl); // layar
			$this->UpdateSort($this->alg_ver, $bCtrl); // alg_ver
			$this->UpdateSort($this->use_realtime, $bCtrl); // use_realtime
			$this->UpdateSort($this->group_realtime, $bCtrl); // group_realtime
			$this->UpdateSort($this->last_download, $bCtrl); // last_download
			$this->UpdateSort($this->ATTLOGStamp, $bCtrl); // ATTLOGStamp
			$this->UpdateSort($this->OPERLOGStamp, $bCtrl); // OPERLOGStamp
			$this->UpdateSort($this->ATTPHOTOStamp, $bCtrl); // ATTPHOTOStamp
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
				$this->sn->setSort("");
				$this->activation_code->setSort("");
				$this->act_code_realtime->setSort("");
				$this->device_name->setSort("");
				$this->comm_key->setSort("");
				$this->dev_id->setSort("");
				$this->comm_type->setSort("");
				$this->ip_address->setSort("");
				$this->id_type->setSort("");
				$this->dev_type->setSort("");
				$this->serial_port->setSort("");
				$this->baud_rate->setSort("");
				$this->ethernet_port->setSort("");
				$this->layar->setSort("");
				$this->alg_ver->setSort("");
				$this->use_realtime->setSort("");
				$this->group_realtime->setSort("");
				$this->last_download->setSort("");
				$this->ATTLOGStamp->setSort("");
				$this->OPERLOGStamp->setSort("");
				$this->ATTPHOTOStamp->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->sn->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fdevicelistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fdevicelistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fdevicelist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fdevicelistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->sn->setDbValue($rs->fields('sn'));
		$this->activation_code->setDbValue($rs->fields('activation_code'));
		$this->act_code_realtime->setDbValue($rs->fields('act_code_realtime'));
		$this->device_name->setDbValue($rs->fields('device_name'));
		$this->comm_key->setDbValue($rs->fields('comm_key'));
		$this->dev_id->setDbValue($rs->fields('dev_id'));
		$this->comm_type->setDbValue($rs->fields('comm_type'));
		$this->ip_address->setDbValue($rs->fields('ip_address'));
		$this->id_type->setDbValue($rs->fields('id_type'));
		$this->dev_type->setDbValue($rs->fields('dev_type'));
		$this->serial_port->setDbValue($rs->fields('serial_port'));
		$this->baud_rate->setDbValue($rs->fields('baud_rate'));
		$this->ethernet_port->setDbValue($rs->fields('ethernet_port'));
		$this->layar->setDbValue($rs->fields('layar'));
		$this->alg_ver->setDbValue($rs->fields('alg_ver'));
		$this->use_realtime->setDbValue($rs->fields('use_realtime'));
		$this->group_realtime->setDbValue($rs->fields('group_realtime'));
		$this->last_download->setDbValue($rs->fields('last_download'));
		$this->ATTLOGStamp->setDbValue($rs->fields('ATTLOGStamp'));
		$this->OPERLOGStamp->setDbValue($rs->fields('OPERLOGStamp'));
		$this->ATTPHOTOStamp->setDbValue($rs->fields('ATTPHOTOStamp'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->sn->DbValue = $row['sn'];
		$this->activation_code->DbValue = $row['activation_code'];
		$this->act_code_realtime->DbValue = $row['act_code_realtime'];
		$this->device_name->DbValue = $row['device_name'];
		$this->comm_key->DbValue = $row['comm_key'];
		$this->dev_id->DbValue = $row['dev_id'];
		$this->comm_type->DbValue = $row['comm_type'];
		$this->ip_address->DbValue = $row['ip_address'];
		$this->id_type->DbValue = $row['id_type'];
		$this->dev_type->DbValue = $row['dev_type'];
		$this->serial_port->DbValue = $row['serial_port'];
		$this->baud_rate->DbValue = $row['baud_rate'];
		$this->ethernet_port->DbValue = $row['ethernet_port'];
		$this->layar->DbValue = $row['layar'];
		$this->alg_ver->DbValue = $row['alg_ver'];
		$this->use_realtime->DbValue = $row['use_realtime'];
		$this->group_realtime->DbValue = $row['group_realtime'];
		$this->last_download->DbValue = $row['last_download'];
		$this->ATTLOGStamp->DbValue = $row['ATTLOGStamp'];
		$this->OPERLOGStamp->DbValue = $row['OPERLOGStamp'];
		$this->ATTPHOTOStamp->DbValue = $row['ATTPHOTOStamp'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("sn")) <> "")
			$this->sn->CurrentValue = $this->getKey("sn"); // sn
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// sn
		// activation_code
		// act_code_realtime
		// device_name
		// comm_key
		// dev_id
		// comm_type
		// ip_address
		// id_type
		// dev_type
		// serial_port
		// baud_rate
		// ethernet_port
		// layar
		// alg_ver
		// use_realtime
		// group_realtime
		// last_download
		// ATTLOGStamp
		// OPERLOGStamp
		// ATTPHOTOStamp

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// sn
		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// activation_code
		$this->activation_code->ViewValue = $this->activation_code->CurrentValue;
		$this->activation_code->ViewCustomAttributes = "";

		// act_code_realtime
		$this->act_code_realtime->ViewValue = $this->act_code_realtime->CurrentValue;
		$this->act_code_realtime->ViewCustomAttributes = "";

		// device_name
		$this->device_name->ViewValue = $this->device_name->CurrentValue;
		$this->device_name->ViewCustomAttributes = "";

		// comm_key
		$this->comm_key->ViewValue = $this->comm_key->CurrentValue;
		$this->comm_key->ViewCustomAttributes = "";

		// dev_id
		$this->dev_id->ViewValue = $this->dev_id->CurrentValue;
		$this->dev_id->ViewCustomAttributes = "";

		// comm_type
		$this->comm_type->ViewValue = $this->comm_type->CurrentValue;
		$this->comm_type->ViewCustomAttributes = "";

		// ip_address
		$this->ip_address->ViewValue = $this->ip_address->CurrentValue;
		$this->ip_address->ViewCustomAttributes = "";

		// id_type
		$this->id_type->ViewValue = $this->id_type->CurrentValue;
		$this->id_type->ViewCustomAttributes = "";

		// dev_type
		$this->dev_type->ViewValue = $this->dev_type->CurrentValue;
		$this->dev_type->ViewCustomAttributes = "";

		// serial_port
		$this->serial_port->ViewValue = $this->serial_port->CurrentValue;
		$this->serial_port->ViewCustomAttributes = "";

		// baud_rate
		$this->baud_rate->ViewValue = $this->baud_rate->CurrentValue;
		$this->baud_rate->ViewCustomAttributes = "";

		// ethernet_port
		$this->ethernet_port->ViewValue = $this->ethernet_port->CurrentValue;
		$this->ethernet_port->ViewCustomAttributes = "";

		// layar
		$this->layar->ViewValue = $this->layar->CurrentValue;
		$this->layar->ViewCustomAttributes = "";

		// alg_ver
		$this->alg_ver->ViewValue = $this->alg_ver->CurrentValue;
		$this->alg_ver->ViewCustomAttributes = "";

		// use_realtime
		$this->use_realtime->ViewValue = $this->use_realtime->CurrentValue;
		$this->use_realtime->ViewCustomAttributes = "";

		// group_realtime
		$this->group_realtime->ViewValue = $this->group_realtime->CurrentValue;
		$this->group_realtime->ViewCustomAttributes = "";

		// last_download
		$this->last_download->ViewValue = $this->last_download->CurrentValue;
		$this->last_download->ViewValue = ew_FormatDateTime($this->last_download->ViewValue, 0);
		$this->last_download->ViewCustomAttributes = "";

		// ATTLOGStamp
		$this->ATTLOGStamp->ViewValue = $this->ATTLOGStamp->CurrentValue;
		$this->ATTLOGStamp->ViewCustomAttributes = "";

		// OPERLOGStamp
		$this->OPERLOGStamp->ViewValue = $this->OPERLOGStamp->CurrentValue;
		$this->OPERLOGStamp->ViewCustomAttributes = "";

		// ATTPHOTOStamp
		$this->ATTPHOTOStamp->ViewValue = $this->ATTPHOTOStamp->CurrentValue;
		$this->ATTPHOTOStamp->ViewCustomAttributes = "";

			// sn
			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";
			$this->sn->TooltipValue = "";

			// activation_code
			$this->activation_code->LinkCustomAttributes = "";
			$this->activation_code->HrefValue = "";
			$this->activation_code->TooltipValue = "";

			// act_code_realtime
			$this->act_code_realtime->LinkCustomAttributes = "";
			$this->act_code_realtime->HrefValue = "";
			$this->act_code_realtime->TooltipValue = "";

			// device_name
			$this->device_name->LinkCustomAttributes = "";
			$this->device_name->HrefValue = "";
			$this->device_name->TooltipValue = "";

			// comm_key
			$this->comm_key->LinkCustomAttributes = "";
			$this->comm_key->HrefValue = "";
			$this->comm_key->TooltipValue = "";

			// dev_id
			$this->dev_id->LinkCustomAttributes = "";
			$this->dev_id->HrefValue = "";
			$this->dev_id->TooltipValue = "";

			// comm_type
			$this->comm_type->LinkCustomAttributes = "";
			$this->comm_type->HrefValue = "";
			$this->comm_type->TooltipValue = "";

			// ip_address
			$this->ip_address->LinkCustomAttributes = "";
			$this->ip_address->HrefValue = "";
			$this->ip_address->TooltipValue = "";

			// id_type
			$this->id_type->LinkCustomAttributes = "";
			$this->id_type->HrefValue = "";
			$this->id_type->TooltipValue = "";

			// dev_type
			$this->dev_type->LinkCustomAttributes = "";
			$this->dev_type->HrefValue = "";
			$this->dev_type->TooltipValue = "";

			// serial_port
			$this->serial_port->LinkCustomAttributes = "";
			$this->serial_port->HrefValue = "";
			$this->serial_port->TooltipValue = "";

			// baud_rate
			$this->baud_rate->LinkCustomAttributes = "";
			$this->baud_rate->HrefValue = "";
			$this->baud_rate->TooltipValue = "";

			// ethernet_port
			$this->ethernet_port->LinkCustomAttributes = "";
			$this->ethernet_port->HrefValue = "";
			$this->ethernet_port->TooltipValue = "";

			// layar
			$this->layar->LinkCustomAttributes = "";
			$this->layar->HrefValue = "";
			$this->layar->TooltipValue = "";

			// alg_ver
			$this->alg_ver->LinkCustomAttributes = "";
			$this->alg_ver->HrefValue = "";
			$this->alg_ver->TooltipValue = "";

			// use_realtime
			$this->use_realtime->LinkCustomAttributes = "";
			$this->use_realtime->HrefValue = "";
			$this->use_realtime->TooltipValue = "";

			// group_realtime
			$this->group_realtime->LinkCustomAttributes = "";
			$this->group_realtime->HrefValue = "";
			$this->group_realtime->TooltipValue = "";

			// last_download
			$this->last_download->LinkCustomAttributes = "";
			$this->last_download->HrefValue = "";
			$this->last_download->TooltipValue = "";

			// ATTLOGStamp
			$this->ATTLOGStamp->LinkCustomAttributes = "";
			$this->ATTLOGStamp->HrefValue = "";
			$this->ATTLOGStamp->TooltipValue = "";

			// OPERLOGStamp
			$this->OPERLOGStamp->LinkCustomAttributes = "";
			$this->OPERLOGStamp->HrefValue = "";
			$this->OPERLOGStamp->TooltipValue = "";

			// ATTPHOTOStamp
			$this->ATTPHOTOStamp->LinkCustomAttributes = "";
			$this->ATTPHOTOStamp->HrefValue = "";
			$this->ATTPHOTOStamp->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_device\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_device',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fdevicelist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($device_list)) $device_list = new cdevice_list();

// Page init
$device_list->Page_Init();

// Page main
$device_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$device_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($device->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fdevicelist = new ew_Form("fdevicelist", "list");
fdevicelist.FormKeyCountName = '<?php echo $device_list->FormKeyCountName ?>';

// Form_CustomValidate event
fdevicelist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdevicelist.ValidateRequired = true;
<?php } else { ?>
fdevicelist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fdevicelistsrch = new ew_Form("fdevicelistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($device->Export == "") { ?>
<div class="ewToolbar">
<?php if ($device->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($device_list->TotalRecs > 0 && $device_list->ExportOptions->Visible()) { ?>
<?php $device_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($device_list->SearchOptions->Visible()) { ?>
<?php $device_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($device_list->FilterOptions->Visible()) { ?>
<?php $device_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($device->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $device_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($device_list->TotalRecs <= 0)
			$device_list->TotalRecs = $device->SelectRecordCount();
	} else {
		if (!$device_list->Recordset && ($device_list->Recordset = $device_list->LoadRecordset()))
			$device_list->TotalRecs = $device_list->Recordset->RecordCount();
	}
	$device_list->StartRec = 1;
	if ($device_list->DisplayRecs <= 0 || ($device->Export <> "" && $device->ExportAll)) // Display all records
		$device_list->DisplayRecs = $device_list->TotalRecs;
	if (!($device->Export <> "" && $device->ExportAll))
		$device_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$device_list->Recordset = $device_list->LoadRecordset($device_list->StartRec-1, $device_list->DisplayRecs);

	// Set no record found message
	if ($device->CurrentAction == "" && $device_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$device_list->setWarningMessage(ew_DeniedMsg());
		if ($device_list->SearchWhere == "0=101")
			$device_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$device_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$device_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($device->Export == "" && $device->CurrentAction == "") { ?>
<form name="fdevicelistsrch" id="fdevicelistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($device_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fdevicelistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="device">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($device_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($device_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $device_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($device_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($device_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($device_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($device_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $device_list->ShowPageHeader(); ?>
<?php
$device_list->ShowMessage();
?>
<?php if ($device_list->TotalRecs > 0 || $device->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid device">
<form name="fdevicelist" id="fdevicelist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($device_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $device_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="device">
<div id="gmp_device" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($device_list->TotalRecs > 0 || $device->CurrentAction == "gridedit") { ?>
<table id="tbl_devicelist" class="table ewTable">
<?php echo $device->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$device_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$device_list->RenderListOptions();

// Render list options (header, left)
$device_list->ListOptions->Render("header", "left");
?>
<?php if ($device->sn->Visible) { // sn ?>
	<?php if ($device->SortUrl($device->sn) == "") { ?>
		<th data-name="sn"><div id="elh_device_sn" class="device_sn"><div class="ewTableHeaderCaption"><?php echo $device->sn->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sn"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->sn) ?>',2);"><div id="elh_device_sn" class="device_sn">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->sn->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($device->sn->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->sn->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->activation_code->Visible) { // activation_code ?>
	<?php if ($device->SortUrl($device->activation_code) == "") { ?>
		<th data-name="activation_code"><div id="elh_device_activation_code" class="device_activation_code"><div class="ewTableHeaderCaption"><?php echo $device->activation_code->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activation_code"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->activation_code) ?>',2);"><div id="elh_device_activation_code" class="device_activation_code">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->activation_code->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($device->activation_code->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->activation_code->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->act_code_realtime->Visible) { // act_code_realtime ?>
	<?php if ($device->SortUrl($device->act_code_realtime) == "") { ?>
		<th data-name="act_code_realtime"><div id="elh_device_act_code_realtime" class="device_act_code_realtime"><div class="ewTableHeaderCaption"><?php echo $device->act_code_realtime->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="act_code_realtime"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->act_code_realtime) ?>',2);"><div id="elh_device_act_code_realtime" class="device_act_code_realtime">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->act_code_realtime->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($device->act_code_realtime->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->act_code_realtime->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->device_name->Visible) { // device_name ?>
	<?php if ($device->SortUrl($device->device_name) == "") { ?>
		<th data-name="device_name"><div id="elh_device_device_name" class="device_device_name"><div class="ewTableHeaderCaption"><?php echo $device->device_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="device_name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->device_name) ?>',2);"><div id="elh_device_device_name" class="device_device_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->device_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($device->device_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->device_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->comm_key->Visible) { // comm_key ?>
	<?php if ($device->SortUrl($device->comm_key) == "") { ?>
		<th data-name="comm_key"><div id="elh_device_comm_key" class="device_comm_key"><div class="ewTableHeaderCaption"><?php echo $device->comm_key->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="comm_key"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->comm_key) ?>',2);"><div id="elh_device_comm_key" class="device_comm_key">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->comm_key->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($device->comm_key->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->comm_key->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->dev_id->Visible) { // dev_id ?>
	<?php if ($device->SortUrl($device->dev_id) == "") { ?>
		<th data-name="dev_id"><div id="elh_device_dev_id" class="device_dev_id"><div class="ewTableHeaderCaption"><?php echo $device->dev_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dev_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->dev_id) ?>',2);"><div id="elh_device_dev_id" class="device_dev_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->dev_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($device->dev_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->dev_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->comm_type->Visible) { // comm_type ?>
	<?php if ($device->SortUrl($device->comm_type) == "") { ?>
		<th data-name="comm_type"><div id="elh_device_comm_type" class="device_comm_type"><div class="ewTableHeaderCaption"><?php echo $device->comm_type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="comm_type"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->comm_type) ?>',2);"><div id="elh_device_comm_type" class="device_comm_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->comm_type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($device->comm_type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->comm_type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->ip_address->Visible) { // ip_address ?>
	<?php if ($device->SortUrl($device->ip_address) == "") { ?>
		<th data-name="ip_address"><div id="elh_device_ip_address" class="device_ip_address"><div class="ewTableHeaderCaption"><?php echo $device->ip_address->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ip_address"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->ip_address) ?>',2);"><div id="elh_device_ip_address" class="device_ip_address">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->ip_address->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($device->ip_address->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->ip_address->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->id_type->Visible) { // id_type ?>
	<?php if ($device->SortUrl($device->id_type) == "") { ?>
		<th data-name="id_type"><div id="elh_device_id_type" class="device_id_type"><div class="ewTableHeaderCaption"><?php echo $device->id_type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_type"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->id_type) ?>',2);"><div id="elh_device_id_type" class="device_id_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->id_type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($device->id_type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->id_type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->dev_type->Visible) { // dev_type ?>
	<?php if ($device->SortUrl($device->dev_type) == "") { ?>
		<th data-name="dev_type"><div id="elh_device_dev_type" class="device_dev_type"><div class="ewTableHeaderCaption"><?php echo $device->dev_type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dev_type"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->dev_type) ?>',2);"><div id="elh_device_dev_type" class="device_dev_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->dev_type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($device->dev_type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->dev_type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->serial_port->Visible) { // serial_port ?>
	<?php if ($device->SortUrl($device->serial_port) == "") { ?>
		<th data-name="serial_port"><div id="elh_device_serial_port" class="device_serial_port"><div class="ewTableHeaderCaption"><?php echo $device->serial_port->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="serial_port"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->serial_port) ?>',2);"><div id="elh_device_serial_port" class="device_serial_port">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->serial_port->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($device->serial_port->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->serial_port->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->baud_rate->Visible) { // baud_rate ?>
	<?php if ($device->SortUrl($device->baud_rate) == "") { ?>
		<th data-name="baud_rate"><div id="elh_device_baud_rate" class="device_baud_rate"><div class="ewTableHeaderCaption"><?php echo $device->baud_rate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="baud_rate"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->baud_rate) ?>',2);"><div id="elh_device_baud_rate" class="device_baud_rate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->baud_rate->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($device->baud_rate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->baud_rate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->ethernet_port->Visible) { // ethernet_port ?>
	<?php if ($device->SortUrl($device->ethernet_port) == "") { ?>
		<th data-name="ethernet_port"><div id="elh_device_ethernet_port" class="device_ethernet_port"><div class="ewTableHeaderCaption"><?php echo $device->ethernet_port->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ethernet_port"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->ethernet_port) ?>',2);"><div id="elh_device_ethernet_port" class="device_ethernet_port">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->ethernet_port->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($device->ethernet_port->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->ethernet_port->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->layar->Visible) { // layar ?>
	<?php if ($device->SortUrl($device->layar) == "") { ?>
		<th data-name="layar"><div id="elh_device_layar" class="device_layar"><div class="ewTableHeaderCaption"><?php echo $device->layar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="layar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->layar) ?>',2);"><div id="elh_device_layar" class="device_layar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->layar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($device->layar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->layar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->alg_ver->Visible) { // alg_ver ?>
	<?php if ($device->SortUrl($device->alg_ver) == "") { ?>
		<th data-name="alg_ver"><div id="elh_device_alg_ver" class="device_alg_ver"><div class="ewTableHeaderCaption"><?php echo $device->alg_ver->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="alg_ver"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->alg_ver) ?>',2);"><div id="elh_device_alg_ver" class="device_alg_ver">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->alg_ver->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($device->alg_ver->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->alg_ver->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->use_realtime->Visible) { // use_realtime ?>
	<?php if ($device->SortUrl($device->use_realtime) == "") { ?>
		<th data-name="use_realtime"><div id="elh_device_use_realtime" class="device_use_realtime"><div class="ewTableHeaderCaption"><?php echo $device->use_realtime->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="use_realtime"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->use_realtime) ?>',2);"><div id="elh_device_use_realtime" class="device_use_realtime">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->use_realtime->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($device->use_realtime->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->use_realtime->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->group_realtime->Visible) { // group_realtime ?>
	<?php if ($device->SortUrl($device->group_realtime) == "") { ?>
		<th data-name="group_realtime"><div id="elh_device_group_realtime" class="device_group_realtime"><div class="ewTableHeaderCaption"><?php echo $device->group_realtime->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="group_realtime"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->group_realtime) ?>',2);"><div id="elh_device_group_realtime" class="device_group_realtime">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->group_realtime->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($device->group_realtime->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->group_realtime->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->last_download->Visible) { // last_download ?>
	<?php if ($device->SortUrl($device->last_download) == "") { ?>
		<th data-name="last_download"><div id="elh_device_last_download" class="device_last_download"><div class="ewTableHeaderCaption"><?php echo $device->last_download->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="last_download"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->last_download) ?>',2);"><div id="elh_device_last_download" class="device_last_download">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->last_download->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($device->last_download->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->last_download->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->ATTLOGStamp->Visible) { // ATTLOGStamp ?>
	<?php if ($device->SortUrl($device->ATTLOGStamp) == "") { ?>
		<th data-name="ATTLOGStamp"><div id="elh_device_ATTLOGStamp" class="device_ATTLOGStamp"><div class="ewTableHeaderCaption"><?php echo $device->ATTLOGStamp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ATTLOGStamp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->ATTLOGStamp) ?>',2);"><div id="elh_device_ATTLOGStamp" class="device_ATTLOGStamp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->ATTLOGStamp->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($device->ATTLOGStamp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->ATTLOGStamp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->OPERLOGStamp->Visible) { // OPERLOGStamp ?>
	<?php if ($device->SortUrl($device->OPERLOGStamp) == "") { ?>
		<th data-name="OPERLOGStamp"><div id="elh_device_OPERLOGStamp" class="device_OPERLOGStamp"><div class="ewTableHeaderCaption"><?php echo $device->OPERLOGStamp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="OPERLOGStamp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->OPERLOGStamp) ?>',2);"><div id="elh_device_OPERLOGStamp" class="device_OPERLOGStamp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->OPERLOGStamp->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($device->OPERLOGStamp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->OPERLOGStamp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($device->ATTPHOTOStamp->Visible) { // ATTPHOTOStamp ?>
	<?php if ($device->SortUrl($device->ATTPHOTOStamp) == "") { ?>
		<th data-name="ATTPHOTOStamp"><div id="elh_device_ATTPHOTOStamp" class="device_ATTPHOTOStamp"><div class="ewTableHeaderCaption"><?php echo $device->ATTPHOTOStamp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ATTPHOTOStamp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $device->SortUrl($device->ATTPHOTOStamp) ?>',2);"><div id="elh_device_ATTPHOTOStamp" class="device_ATTPHOTOStamp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $device->ATTPHOTOStamp->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($device->ATTPHOTOStamp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($device->ATTPHOTOStamp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$device_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($device->ExportAll && $device->Export <> "") {
	$device_list->StopRec = $device_list->TotalRecs;
} else {

	// Set the last record to display
	if ($device_list->TotalRecs > $device_list->StartRec + $device_list->DisplayRecs - 1)
		$device_list->StopRec = $device_list->StartRec + $device_list->DisplayRecs - 1;
	else
		$device_list->StopRec = $device_list->TotalRecs;
}
$device_list->RecCnt = $device_list->StartRec - 1;
if ($device_list->Recordset && !$device_list->Recordset->EOF) {
	$device_list->Recordset->MoveFirst();
	$bSelectLimit = $device_list->UseSelectLimit;
	if (!$bSelectLimit && $device_list->StartRec > 1)
		$device_list->Recordset->Move($device_list->StartRec - 1);
} elseif (!$device->AllowAddDeleteRow && $device_list->StopRec == 0) {
	$device_list->StopRec = $device->GridAddRowCount;
}

// Initialize aggregate
$device->RowType = EW_ROWTYPE_AGGREGATEINIT;
$device->ResetAttrs();
$device_list->RenderRow();
while ($device_list->RecCnt < $device_list->StopRec) {
	$device_list->RecCnt++;
	if (intval($device_list->RecCnt) >= intval($device_list->StartRec)) {
		$device_list->RowCnt++;

		// Set up key count
		$device_list->KeyCount = $device_list->RowIndex;

		// Init row class and style
		$device->ResetAttrs();
		$device->CssClass = "";
		if ($device->CurrentAction == "gridadd") {
		} else {
			$device_list->LoadRowValues($device_list->Recordset); // Load row values
		}
		$device->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$device->RowAttrs = array_merge($device->RowAttrs, array('data-rowindex'=>$device_list->RowCnt, 'id'=>'r' . $device_list->RowCnt . '_device', 'data-rowtype'=>$device->RowType));

		// Render row
		$device_list->RenderRow();

		// Render list options
		$device_list->RenderListOptions();
?>
	<tr<?php echo $device->RowAttributes() ?>>
<?php

// Render list options (body, left)
$device_list->ListOptions->Render("body", "left", $device_list->RowCnt);
?>
	<?php if ($device->sn->Visible) { // sn ?>
		<td data-name="sn"<?php echo $device->sn->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_sn" class="device_sn">
<span<?php echo $device->sn->ViewAttributes() ?>>
<?php echo $device->sn->ListViewValue() ?></span>
</span>
<a id="<?php echo $device_list->PageObjName . "_row_" . $device_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($device->activation_code->Visible) { // activation_code ?>
		<td data-name="activation_code"<?php echo $device->activation_code->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_activation_code" class="device_activation_code">
<span<?php echo $device->activation_code->ViewAttributes() ?>>
<?php echo $device->activation_code->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->act_code_realtime->Visible) { // act_code_realtime ?>
		<td data-name="act_code_realtime"<?php echo $device->act_code_realtime->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_act_code_realtime" class="device_act_code_realtime">
<span<?php echo $device->act_code_realtime->ViewAttributes() ?>>
<?php echo $device->act_code_realtime->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->device_name->Visible) { // device_name ?>
		<td data-name="device_name"<?php echo $device->device_name->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_device_name" class="device_device_name">
<span<?php echo $device->device_name->ViewAttributes() ?>>
<?php echo $device->device_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->comm_key->Visible) { // comm_key ?>
		<td data-name="comm_key"<?php echo $device->comm_key->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_comm_key" class="device_comm_key">
<span<?php echo $device->comm_key->ViewAttributes() ?>>
<?php echo $device->comm_key->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->dev_id->Visible) { // dev_id ?>
		<td data-name="dev_id"<?php echo $device->dev_id->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_dev_id" class="device_dev_id">
<span<?php echo $device->dev_id->ViewAttributes() ?>>
<?php echo $device->dev_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->comm_type->Visible) { // comm_type ?>
		<td data-name="comm_type"<?php echo $device->comm_type->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_comm_type" class="device_comm_type">
<span<?php echo $device->comm_type->ViewAttributes() ?>>
<?php echo $device->comm_type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->ip_address->Visible) { // ip_address ?>
		<td data-name="ip_address"<?php echo $device->ip_address->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_ip_address" class="device_ip_address">
<span<?php echo $device->ip_address->ViewAttributes() ?>>
<?php echo $device->ip_address->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->id_type->Visible) { // id_type ?>
		<td data-name="id_type"<?php echo $device->id_type->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_id_type" class="device_id_type">
<span<?php echo $device->id_type->ViewAttributes() ?>>
<?php echo $device->id_type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->dev_type->Visible) { // dev_type ?>
		<td data-name="dev_type"<?php echo $device->dev_type->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_dev_type" class="device_dev_type">
<span<?php echo $device->dev_type->ViewAttributes() ?>>
<?php echo $device->dev_type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->serial_port->Visible) { // serial_port ?>
		<td data-name="serial_port"<?php echo $device->serial_port->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_serial_port" class="device_serial_port">
<span<?php echo $device->serial_port->ViewAttributes() ?>>
<?php echo $device->serial_port->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->baud_rate->Visible) { // baud_rate ?>
		<td data-name="baud_rate"<?php echo $device->baud_rate->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_baud_rate" class="device_baud_rate">
<span<?php echo $device->baud_rate->ViewAttributes() ?>>
<?php echo $device->baud_rate->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->ethernet_port->Visible) { // ethernet_port ?>
		<td data-name="ethernet_port"<?php echo $device->ethernet_port->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_ethernet_port" class="device_ethernet_port">
<span<?php echo $device->ethernet_port->ViewAttributes() ?>>
<?php echo $device->ethernet_port->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->layar->Visible) { // layar ?>
		<td data-name="layar"<?php echo $device->layar->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_layar" class="device_layar">
<span<?php echo $device->layar->ViewAttributes() ?>>
<?php echo $device->layar->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->alg_ver->Visible) { // alg_ver ?>
		<td data-name="alg_ver"<?php echo $device->alg_ver->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_alg_ver" class="device_alg_ver">
<span<?php echo $device->alg_ver->ViewAttributes() ?>>
<?php echo $device->alg_ver->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->use_realtime->Visible) { // use_realtime ?>
		<td data-name="use_realtime"<?php echo $device->use_realtime->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_use_realtime" class="device_use_realtime">
<span<?php echo $device->use_realtime->ViewAttributes() ?>>
<?php echo $device->use_realtime->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->group_realtime->Visible) { // group_realtime ?>
		<td data-name="group_realtime"<?php echo $device->group_realtime->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_group_realtime" class="device_group_realtime">
<span<?php echo $device->group_realtime->ViewAttributes() ?>>
<?php echo $device->group_realtime->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->last_download->Visible) { // last_download ?>
		<td data-name="last_download"<?php echo $device->last_download->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_last_download" class="device_last_download">
<span<?php echo $device->last_download->ViewAttributes() ?>>
<?php echo $device->last_download->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->ATTLOGStamp->Visible) { // ATTLOGStamp ?>
		<td data-name="ATTLOGStamp"<?php echo $device->ATTLOGStamp->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_ATTLOGStamp" class="device_ATTLOGStamp">
<span<?php echo $device->ATTLOGStamp->ViewAttributes() ?>>
<?php echo $device->ATTLOGStamp->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->OPERLOGStamp->Visible) { // OPERLOGStamp ?>
		<td data-name="OPERLOGStamp"<?php echo $device->OPERLOGStamp->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_OPERLOGStamp" class="device_OPERLOGStamp">
<span<?php echo $device->OPERLOGStamp->ViewAttributes() ?>>
<?php echo $device->OPERLOGStamp->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($device->ATTPHOTOStamp->Visible) { // ATTPHOTOStamp ?>
		<td data-name="ATTPHOTOStamp"<?php echo $device->ATTPHOTOStamp->CellAttributes() ?>>
<span id="el<?php echo $device_list->RowCnt ?>_device_ATTPHOTOStamp" class="device_ATTPHOTOStamp">
<span<?php echo $device->ATTPHOTOStamp->ViewAttributes() ?>>
<?php echo $device->ATTPHOTOStamp->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$device_list->ListOptions->Render("body", "right", $device_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($device->CurrentAction <> "gridadd")
		$device_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($device->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($device_list->Recordset)
	$device_list->Recordset->Close();
?>
<?php if ($device->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($device->CurrentAction <> "gridadd" && $device->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($device_list->Pager)) $device_list->Pager = new cPrevNextPager($device_list->StartRec, $device_list->DisplayRecs, $device_list->TotalRecs) ?>
<?php if ($device_list->Pager->RecordCount > 0 && $device_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($device_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $device_list->PageUrl() ?>start=<?php echo $device_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($device_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $device_list->PageUrl() ?>start=<?php echo $device_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $device_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($device_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $device_list->PageUrl() ?>start=<?php echo $device_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($device_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $device_list->PageUrl() ?>start=<?php echo $device_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $device_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $device_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $device_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $device_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($device_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($device_list->TotalRecs == 0 && $device->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($device_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($device->Export == "") { ?>
<script type="text/javascript">
fdevicelistsrch.FilterList = <?php echo $device_list->GetFilterList() ?>;
fdevicelistsrch.Init();
fdevicelist.Init();
</script>
<?php } ?>
<?php
$device_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($device->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$device_list->Page_Terminate();
?>
