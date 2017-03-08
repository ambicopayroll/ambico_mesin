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

$jam_kerja_view = NULL; // Initialize page object first

class cjam_kerja_view extends cjam_kerja {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'jam_kerja';

	// Page object name
	var $PageObjName = 'jam_kerja_view';

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
		$KeyUrl = "";
		if (@$_GET["jk_id"] <> "") {
			$this->RecKey["jk_id"] = $_GET["jk_id"];
			$KeyUrl .= "&amp;jk_id=" . urlencode($this->RecKey["jk_id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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
		if (@$_GET["jk_id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["jk_id"]);
		}

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["jk_id"] <> "") {
				$this->jk_id->setQueryStringValue($_GET["jk_id"]);
				$this->RecKey["jk_id"] = $this->jk_id->QueryStringValue;
			} elseif (@$_POST["jk_id"] <> "") {
				$this->jk_id->setFormValue($_POST["jk_id"]);
				$this->RecKey["jk_id"] = $this->jk_id->FormValue;
			} else {
				$sReturnUrl = "jam_kerjalist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "jam_kerjalist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "jam_kerjalist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->CopyUrl) . "',caption:'" . $copycaption . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_AddQueryStringToUrl($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_jam_kerja\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_jam_kerja',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fjam_kerjaview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

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
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
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
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
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

		// Add record key QueryString
		$sQry .= "&" . substr($this->KeyUrl("", ""), 1);
		return $sQry;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("jam_kerjalist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($jam_kerja_view)) $jam_kerja_view = new cjam_kerja_view();

// Page init
$jam_kerja_view->Page_Init();

// Page main
$jam_kerja_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jam_kerja_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($jam_kerja->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fjam_kerjaview = new ew_Form("fjam_kerjaview", "view");

// Form_CustomValidate event
fjam_kerjaview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjam_kerjaview.ValidateRequired = true;
<?php } else { ?>
fjam_kerjaview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($jam_kerja->Export == "") { ?>
<div class="ewToolbar">
<?php if (!$jam_kerja_view->IsModal) { ?>
<?php if ($jam_kerja->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php } ?>
<?php $jam_kerja_view->ExportOptions->Render("body") ?>
<?php
	foreach ($jam_kerja_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$jam_kerja_view->IsModal) { ?>
<?php if ($jam_kerja->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $jam_kerja_view->ShowPageHeader(); ?>
<?php
$jam_kerja_view->ShowMessage();
?>
<form name="fjam_kerjaview" id="fjam_kerjaview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jam_kerja_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jam_kerja_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jam_kerja">
<?php if ($jam_kerja_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($jam_kerja->jk_id->Visible) { // jk_id ?>
	<tr id="r_jk_id">
		<td><span id="elh_jam_kerja_jk_id"><?php echo $jam_kerja->jk_id->FldCaption() ?></span></td>
		<td data-name="jk_id"<?php echo $jam_kerja->jk_id->CellAttributes() ?>>
<span id="el_jam_kerja_jk_id">
<span<?php echo $jam_kerja->jk_id->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_name->Visible) { // jk_name ?>
	<tr id="r_jk_name">
		<td><span id="elh_jam_kerja_jk_name"><?php echo $jam_kerja->jk_name->FldCaption() ?></span></td>
		<td data-name="jk_name"<?php echo $jam_kerja->jk_name->CellAttributes() ?>>
<span id="el_jam_kerja_jk_name">
<span<?php echo $jam_kerja->jk_name->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_kode->Visible) { // jk_kode ?>
	<tr id="r_jk_kode">
		<td><span id="elh_jam_kerja_jk_kode"><?php echo $jam_kerja->jk_kode->FldCaption() ?></span></td>
		<td data-name="jk_kode"<?php echo $jam_kerja->jk_kode->CellAttributes() ?>>
<span id="el_jam_kerja_jk_kode">
<span<?php echo $jam_kerja->jk_kode->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_kode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->use_set->Visible) { // use_set ?>
	<tr id="r_use_set">
		<td><span id="elh_jam_kerja_use_set"><?php echo $jam_kerja->use_set->FldCaption() ?></span></td>
		<td data-name="use_set"<?php echo $jam_kerja->use_set->CellAttributes() ?>>
<span id="el_jam_kerja_use_set">
<span<?php echo $jam_kerja->use_set->ViewAttributes() ?>>
<?php echo $jam_kerja->use_set->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_bcin->Visible) { // jk_bcin ?>
	<tr id="r_jk_bcin">
		<td><span id="elh_jam_kerja_jk_bcin"><?php echo $jam_kerja->jk_bcin->FldCaption() ?></span></td>
		<td data-name="jk_bcin"<?php echo $jam_kerja->jk_bcin->CellAttributes() ?>>
<span id="el_jam_kerja_jk_bcin">
<span<?php echo $jam_kerja->jk_bcin->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_bcin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_cin->Visible) { // jk_cin ?>
	<tr id="r_jk_cin">
		<td><span id="elh_jam_kerja_jk_cin"><?php echo $jam_kerja->jk_cin->FldCaption() ?></span></td>
		<td data-name="jk_cin"<?php echo $jam_kerja->jk_cin->CellAttributes() ?>>
<span id="el_jam_kerja_jk_cin">
<span<?php echo $jam_kerja->jk_cin->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_cin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_ecin->Visible) { // jk_ecin ?>
	<tr id="r_jk_ecin">
		<td><span id="elh_jam_kerja_jk_ecin"><?php echo $jam_kerja->jk_ecin->FldCaption() ?></span></td>
		<td data-name="jk_ecin"<?php echo $jam_kerja->jk_ecin->CellAttributes() ?>>
<span id="el_jam_kerja_jk_ecin">
<span<?php echo $jam_kerja->jk_ecin->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ecin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_tol_late->Visible) { // jk_tol_late ?>
	<tr id="r_jk_tol_late">
		<td><span id="elh_jam_kerja_jk_tol_late"><?php echo $jam_kerja->jk_tol_late->FldCaption() ?></span></td>
		<td data-name="jk_tol_late"<?php echo $jam_kerja->jk_tol_late->CellAttributes() ?>>
<span id="el_jam_kerja_jk_tol_late">
<span<?php echo $jam_kerja->jk_tol_late->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_tol_late->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_use_ist->Visible) { // jk_use_ist ?>
	<tr id="r_jk_use_ist">
		<td><span id="elh_jam_kerja_jk_use_ist"><?php echo $jam_kerja->jk_use_ist->FldCaption() ?></span></td>
		<td data-name="jk_use_ist"<?php echo $jam_kerja->jk_use_ist->CellAttributes() ?>>
<span id="el_jam_kerja_jk_use_ist">
<span<?php echo $jam_kerja->jk_use_ist->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_use_ist->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_ist1->Visible) { // jk_ist1 ?>
	<tr id="r_jk_ist1">
		<td><span id="elh_jam_kerja_jk_ist1"><?php echo $jam_kerja->jk_ist1->FldCaption() ?></span></td>
		<td data-name="jk_ist1"<?php echo $jam_kerja->jk_ist1->CellAttributes() ?>>
<span id="el_jam_kerja_jk_ist1">
<span<?php echo $jam_kerja->jk_ist1->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ist1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_ist2->Visible) { // jk_ist2 ?>
	<tr id="r_jk_ist2">
		<td><span id="elh_jam_kerja_jk_ist2"><?php echo $jam_kerja->jk_ist2->FldCaption() ?></span></td>
		<td data-name="jk_ist2"<?php echo $jam_kerja->jk_ist2->CellAttributes() ?>>
<span id="el_jam_kerja_jk_ist2">
<span<?php echo $jam_kerja->jk_ist2->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ist2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_tol_early->Visible) { // jk_tol_early ?>
	<tr id="r_jk_tol_early">
		<td><span id="elh_jam_kerja_jk_tol_early"><?php echo $jam_kerja->jk_tol_early->FldCaption() ?></span></td>
		<td data-name="jk_tol_early"<?php echo $jam_kerja->jk_tol_early->CellAttributes() ?>>
<span id="el_jam_kerja_jk_tol_early">
<span<?php echo $jam_kerja->jk_tol_early->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_tol_early->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_bcout->Visible) { // jk_bcout ?>
	<tr id="r_jk_bcout">
		<td><span id="elh_jam_kerja_jk_bcout"><?php echo $jam_kerja->jk_bcout->FldCaption() ?></span></td>
		<td data-name="jk_bcout"<?php echo $jam_kerja->jk_bcout->CellAttributes() ?>>
<span id="el_jam_kerja_jk_bcout">
<span<?php echo $jam_kerja->jk_bcout->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_bcout->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_cout->Visible) { // jk_cout ?>
	<tr id="r_jk_cout">
		<td><span id="elh_jam_kerja_jk_cout"><?php echo $jam_kerja->jk_cout->FldCaption() ?></span></td>
		<td data-name="jk_cout"<?php echo $jam_kerja->jk_cout->CellAttributes() ?>>
<span id="el_jam_kerja_jk_cout">
<span<?php echo $jam_kerja->jk_cout->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_cout->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_ecout->Visible) { // jk_ecout ?>
	<tr id="r_jk_ecout">
		<td><span id="elh_jam_kerja_jk_ecout"><?php echo $jam_kerja->jk_ecout->FldCaption() ?></span></td>
		<td data-name="jk_ecout"<?php echo $jam_kerja->jk_ecout->CellAttributes() ?>>
<span id="el_jam_kerja_jk_ecout">
<span<?php echo $jam_kerja->jk_ecout->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ecout->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->use_eot->Visible) { // use_eot ?>
	<tr id="r_use_eot">
		<td><span id="elh_jam_kerja_use_eot"><?php echo $jam_kerja->use_eot->FldCaption() ?></span></td>
		<td data-name="use_eot"<?php echo $jam_kerja->use_eot->CellAttributes() ?>>
<span id="el_jam_kerja_use_eot">
<span<?php echo $jam_kerja->use_eot->ViewAttributes() ?>>
<?php echo $jam_kerja->use_eot->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->min_eot->Visible) { // min_eot ?>
	<tr id="r_min_eot">
		<td><span id="elh_jam_kerja_min_eot"><?php echo $jam_kerja->min_eot->FldCaption() ?></span></td>
		<td data-name="min_eot"<?php echo $jam_kerja->min_eot->CellAttributes() ?>>
<span id="el_jam_kerja_min_eot">
<span<?php echo $jam_kerja->min_eot->ViewAttributes() ?>>
<?php echo $jam_kerja->min_eot->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->max_eot->Visible) { // max_eot ?>
	<tr id="r_max_eot">
		<td><span id="elh_jam_kerja_max_eot"><?php echo $jam_kerja->max_eot->FldCaption() ?></span></td>
		<td data-name="max_eot"<?php echo $jam_kerja->max_eot->CellAttributes() ?>>
<span id="el_jam_kerja_max_eot">
<span<?php echo $jam_kerja->max_eot->ViewAttributes() ?>>
<?php echo $jam_kerja->max_eot->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->reduce_eot->Visible) { // reduce_eot ?>
	<tr id="r_reduce_eot">
		<td><span id="elh_jam_kerja_reduce_eot"><?php echo $jam_kerja->reduce_eot->FldCaption() ?></span></td>
		<td data-name="reduce_eot"<?php echo $jam_kerja->reduce_eot->CellAttributes() ?>>
<span id="el_jam_kerja_reduce_eot">
<span<?php echo $jam_kerja->reduce_eot->ViewAttributes() ?>>
<?php echo $jam_kerja->reduce_eot->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_durasi->Visible) { // jk_durasi ?>
	<tr id="r_jk_durasi">
		<td><span id="elh_jam_kerja_jk_durasi"><?php echo $jam_kerja->jk_durasi->FldCaption() ?></span></td>
		<td data-name="jk_durasi"<?php echo $jam_kerja->jk_durasi->CellAttributes() ?>>
<span id="el_jam_kerja_jk_durasi">
<span<?php echo $jam_kerja->jk_durasi->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_durasi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_countas->Visible) { // jk_countas ?>
	<tr id="r_jk_countas">
		<td><span id="elh_jam_kerja_jk_countas"><?php echo $jam_kerja->jk_countas->FldCaption() ?></span></td>
		<td data-name="jk_countas"<?php echo $jam_kerja->jk_countas->CellAttributes() ?>>
<span id="el_jam_kerja_jk_countas">
<span<?php echo $jam_kerja->jk_countas->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_countas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_min_countas->Visible) { // jk_min_countas ?>
	<tr id="r_jk_min_countas">
		<td><span id="elh_jam_kerja_jk_min_countas"><?php echo $jam_kerja->jk_min_countas->FldCaption() ?></span></td>
		<td data-name="jk_min_countas"<?php echo $jam_kerja->jk_min_countas->CellAttributes() ?>>
<span id="el_jam_kerja_jk_min_countas">
<span<?php echo $jam_kerja->jk_min_countas->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_min_countas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($jam_kerja->jk_ket->Visible) { // jk_ket ?>
	<tr id="r_jk_ket">
		<td><span id="elh_jam_kerja_jk_ket"><?php echo $jam_kerja->jk_ket->FldCaption() ?></span></td>
		<td data-name="jk_ket"<?php echo $jam_kerja->jk_ket->CellAttributes() ?>>
<span id="el_jam_kerja_jk_ket">
<span<?php echo $jam_kerja->jk_ket->ViewAttributes() ?>>
<?php echo $jam_kerja->jk_ket->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php if ($jam_kerja->Export == "") { ?>
<script type="text/javascript">
fjam_kerjaview.Init();
</script>
<?php } ?>
<?php
$jam_kerja_view->ShowPageFooter();
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
$jam_kerja_view->Page_Terminate();
?>
