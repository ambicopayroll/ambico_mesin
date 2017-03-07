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

$zx_kredit_m_view = NULL; // Initialize page object first

class czx_kredit_m_view extends czx_kredit_m {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'zx_kredit_m';

	// Page object name
	var $PageObjName = 'zx_kredit_m_view';

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
		$KeyUrl = "";
		if (@$_GET["id_kredit"] <> "") {
			$this->RecKey["id_kredit"] = $_GET["id_kredit"];
			$KeyUrl .= "&amp;id_kredit=" . urlencode($this->RecKey["id_kredit"]);
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
				$this->Page_Terminate(ew_GetUrl("zx_kredit_mlist.php"));
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
		if (@$_GET["id_kredit"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["id_kredit"]);
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
		$this->keterangan->SetVisibility();
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
			if (@$_GET["id_kredit"] <> "") {
				$this->id_kredit->setQueryStringValue($_GET["id_kredit"]);
				$this->RecKey["id_kredit"] = $this->id_kredit->QueryStringValue;
			} elseif (@$_POST["id_kredit"] <> "") {
				$this->id_kredit->setFormValue($_POST["id_kredit"]);
				$this->RecKey["id_kredit"] = $this->id_kredit->FormValue;
			} else {
				$sReturnUrl = "zx_kredit_mlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "zx_kredit_mlist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "zx_kredit_mlist.php"; // Not page request, return to list
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

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

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

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";

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
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_zx_kredit_m\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_zx_kredit_m',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fzx_kredit_mview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("zx_kredit_mlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($zx_kredit_m_view)) $zx_kredit_m_view = new czx_kredit_m_view();

// Page init
$zx_kredit_m_view->Page_Init();

// Page main
$zx_kredit_m_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$zx_kredit_m_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($zx_kredit_m->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fzx_kredit_mview = new ew_Form("fzx_kredit_mview", "view");

// Form_CustomValidate event
fzx_kredit_mview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fzx_kredit_mview.ValidateRequired = true;
<?php } else { ?>
fzx_kredit_mview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($zx_kredit_m->Export == "") { ?>
<div class="ewToolbar">
<?php if (!$zx_kredit_m_view->IsModal) { ?>
<?php if ($zx_kredit_m->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php } ?>
<?php $zx_kredit_m_view->ExportOptions->Render("body") ?>
<?php
	foreach ($zx_kredit_m_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$zx_kredit_m_view->IsModal) { ?>
<?php if ($zx_kredit_m->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $zx_kredit_m_view->ShowPageHeader(); ?>
<?php
$zx_kredit_m_view->ShowMessage();
?>
<form name="fzx_kredit_mview" id="fzx_kredit_mview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($zx_kredit_m_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $zx_kredit_m_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="zx_kredit_m">
<?php if ($zx_kredit_m_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($zx_kredit_m->id_kredit->Visible) { // id_kredit ?>
	<tr id="r_id_kredit">
		<td><span id="elh_zx_kredit_m_id_kredit"><?php echo $zx_kredit_m->id_kredit->FldCaption() ?></span></td>
		<td data-name="id_kredit"<?php echo $zx_kredit_m->id_kredit->CellAttributes() ?>>
<span id="el_zx_kredit_m_id_kredit">
<span<?php echo $zx_kredit_m->id_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_m->id_kredit->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->no_kredit->Visible) { // no_kredit ?>
	<tr id="r_no_kredit">
		<td><span id="elh_zx_kredit_m_no_kredit"><?php echo $zx_kredit_m->no_kredit->FldCaption() ?></span></td>
		<td data-name="no_kredit"<?php echo $zx_kredit_m->no_kredit->CellAttributes() ?>>
<span id="el_zx_kredit_m_no_kredit">
<span<?php echo $zx_kredit_m->no_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_m->no_kredit->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->tgl_kredit->Visible) { // tgl_kredit ?>
	<tr id="r_tgl_kredit">
		<td><span id="elh_zx_kredit_m_tgl_kredit"><?php echo $zx_kredit_m->tgl_kredit->FldCaption() ?></span></td>
		<td data-name="tgl_kredit"<?php echo $zx_kredit_m->tgl_kredit->CellAttributes() ?>>
<span id="el_zx_kredit_m_tgl_kredit">
<span<?php echo $zx_kredit_m->tgl_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tgl_kredit->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->emp_id_auto->Visible) { // emp_id_auto ?>
	<tr id="r_emp_id_auto">
		<td><span id="elh_zx_kredit_m_emp_id_auto"><?php echo $zx_kredit_m->emp_id_auto->FldCaption() ?></span></td>
		<td data-name="emp_id_auto"<?php echo $zx_kredit_m->emp_id_auto->CellAttributes() ?>>
<span id="el_zx_kredit_m_emp_id_auto">
<span<?php echo $zx_kredit_m->emp_id_auto->ViewAttributes() ?>>
<?php echo $zx_kredit_m->emp_id_auto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->krd_id->Visible) { // krd_id ?>
	<tr id="r_krd_id">
		<td><span id="elh_zx_kredit_m_krd_id"><?php echo $zx_kredit_m->krd_id->FldCaption() ?></span></td>
		<td data-name="krd_id"<?php echo $zx_kredit_m->krd_id->CellAttributes() ?>>
<span id="el_zx_kredit_m_krd_id">
<span<?php echo $zx_kredit_m->krd_id->ViewAttributes() ?>>
<?php echo $zx_kredit_m->krd_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->cara_hitung->Visible) { // cara_hitung ?>
	<tr id="r_cara_hitung">
		<td><span id="elh_zx_kredit_m_cara_hitung"><?php echo $zx_kredit_m->cara_hitung->FldCaption() ?></span></td>
		<td data-name="cara_hitung"<?php echo $zx_kredit_m->cara_hitung->CellAttributes() ?>>
<span id="el_zx_kredit_m_cara_hitung">
<span<?php echo $zx_kredit_m->cara_hitung->ViewAttributes() ?>>
<?php echo $zx_kredit_m->cara_hitung->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->tot_kredit->Visible) { // tot_kredit ?>
	<tr id="r_tot_kredit">
		<td><span id="elh_zx_kredit_m_tot_kredit"><?php echo $zx_kredit_m->tot_kredit->FldCaption() ?></span></td>
		<td data-name="tot_kredit"<?php echo $zx_kredit_m->tot_kredit->CellAttributes() ?>>
<span id="el_zx_kredit_m_tot_kredit">
<span<?php echo $zx_kredit_m->tot_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tot_kredit->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->saldo_aw->Visible) { // saldo_aw ?>
	<tr id="r_saldo_aw">
		<td><span id="elh_zx_kredit_m_saldo_aw"><?php echo $zx_kredit_m->saldo_aw->FldCaption() ?></span></td>
		<td data-name="saldo_aw"<?php echo $zx_kredit_m->saldo_aw->CellAttributes() ?>>
<span id="el_zx_kredit_m_saldo_aw">
<span<?php echo $zx_kredit_m->saldo_aw->ViewAttributes() ?>>
<?php echo $zx_kredit_m->saldo_aw->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->suku_bunga->Visible) { // suku_bunga ?>
	<tr id="r_suku_bunga">
		<td><span id="elh_zx_kredit_m_suku_bunga"><?php echo $zx_kredit_m->suku_bunga->FldCaption() ?></span></td>
		<td data-name="suku_bunga"<?php echo $zx_kredit_m->suku_bunga->CellAttributes() ?>>
<span id="el_zx_kredit_m_suku_bunga">
<span<?php echo $zx_kredit_m->suku_bunga->ViewAttributes() ?>>
<?php echo $zx_kredit_m->suku_bunga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->periode_bulan->Visible) { // periode_bulan ?>
	<tr id="r_periode_bulan">
		<td><span id="elh_zx_kredit_m_periode_bulan"><?php echo $zx_kredit_m->periode_bulan->FldCaption() ?></span></td>
		<td data-name="periode_bulan"<?php echo $zx_kredit_m->periode_bulan->CellAttributes() ?>>
<span id="el_zx_kredit_m_periode_bulan">
<span<?php echo $zx_kredit_m->periode_bulan->ViewAttributes() ?>>
<?php echo $zx_kredit_m->periode_bulan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->angs_pokok->Visible) { // angs_pokok ?>
	<tr id="r_angs_pokok">
		<td><span id="elh_zx_kredit_m_angs_pokok"><?php echo $zx_kredit_m->angs_pokok->FldCaption() ?></span></td>
		<td data-name="angs_pokok"<?php echo $zx_kredit_m->angs_pokok->CellAttributes() ?>>
<span id="el_zx_kredit_m_angs_pokok">
<span<?php echo $zx_kredit_m->angs_pokok->ViewAttributes() ?>>
<?php echo $zx_kredit_m->angs_pokok->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->angs_pertama->Visible) { // angs_pertama ?>
	<tr id="r_angs_pertama">
		<td><span id="elh_zx_kredit_m_angs_pertama"><?php echo $zx_kredit_m->angs_pertama->FldCaption() ?></span></td>
		<td data-name="angs_pertama"<?php echo $zx_kredit_m->angs_pertama->CellAttributes() ?>>
<span id="el_zx_kredit_m_angs_pertama">
<span<?php echo $zx_kredit_m->angs_pertama->ViewAttributes() ?>>
<?php echo $zx_kredit_m->angs_pertama->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->tot_debet->Visible) { // tot_debet ?>
	<tr id="r_tot_debet">
		<td><span id="elh_zx_kredit_m_tot_debet"><?php echo $zx_kredit_m->tot_debet->FldCaption() ?></span></td>
		<td data-name="tot_debet"<?php echo $zx_kredit_m->tot_debet->CellAttributes() ?>>
<span id="el_zx_kredit_m_tot_debet">
<span<?php echo $zx_kredit_m->tot_debet->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tot_debet->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->tot_angs_pokok->Visible) { // tot_angs_pokok ?>
	<tr id="r_tot_angs_pokok">
		<td><span id="elh_zx_kredit_m_tot_angs_pokok"><?php echo $zx_kredit_m->tot_angs_pokok->FldCaption() ?></span></td>
		<td data-name="tot_angs_pokok"<?php echo $zx_kredit_m->tot_angs_pokok->CellAttributes() ?>>
<span id="el_zx_kredit_m_tot_angs_pokok">
<span<?php echo $zx_kredit_m->tot_angs_pokok->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tot_angs_pokok->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->tot_bunga->Visible) { // tot_bunga ?>
	<tr id="r_tot_bunga">
		<td><span id="elh_zx_kredit_m_tot_bunga"><?php echo $zx_kredit_m->tot_bunga->FldCaption() ?></span></td>
		<td data-name="tot_bunga"<?php echo $zx_kredit_m->tot_bunga->CellAttributes() ?>>
<span id="el_zx_kredit_m_tot_bunga">
<span<?php echo $zx_kredit_m->tot_bunga->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tot_bunga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->def_pembulatan->Visible) { // def_pembulatan ?>
	<tr id="r_def_pembulatan">
		<td><span id="elh_zx_kredit_m_def_pembulatan"><?php echo $zx_kredit_m->def_pembulatan->FldCaption() ?></span></td>
		<td data-name="def_pembulatan"<?php echo $zx_kredit_m->def_pembulatan->CellAttributes() ?>>
<span id="el_zx_kredit_m_def_pembulatan">
<span<?php echo $zx_kredit_m->def_pembulatan->ViewAttributes() ?>>
<?php echo $zx_kredit_m->def_pembulatan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->jumlah_piutang->Visible) { // jumlah_piutang ?>
	<tr id="r_jumlah_piutang">
		<td><span id="elh_zx_kredit_m_jumlah_piutang"><?php echo $zx_kredit_m->jumlah_piutang->FldCaption() ?></span></td>
		<td data-name="jumlah_piutang"<?php echo $zx_kredit_m->jumlah_piutang->CellAttributes() ?>>
<span id="el_zx_kredit_m_jumlah_piutang">
<span<?php echo $zx_kredit_m->jumlah_piutang->ViewAttributes() ?>>
<?php echo $zx_kredit_m->jumlah_piutang->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->approv_by->Visible) { // approv_by ?>
	<tr id="r_approv_by">
		<td><span id="elh_zx_kredit_m_approv_by"><?php echo $zx_kredit_m->approv_by->FldCaption() ?></span></td>
		<td data-name="approv_by"<?php echo $zx_kredit_m->approv_by->CellAttributes() ?>>
<span id="el_zx_kredit_m_approv_by">
<span<?php echo $zx_kredit_m->approv_by->ViewAttributes() ?>>
<?php echo $zx_kredit_m->approv_by->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->keterangan->Visible) { // keterangan ?>
	<tr id="r_keterangan">
		<td><span id="elh_zx_kredit_m_keterangan"><?php echo $zx_kredit_m->keterangan->FldCaption() ?></span></td>
		<td data-name="keterangan"<?php echo $zx_kredit_m->keterangan->CellAttributes() ?>>
<span id="el_zx_kredit_m_keterangan">
<span<?php echo $zx_kredit_m->keterangan->ViewAttributes() ?>>
<?php echo $zx_kredit_m->keterangan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_zx_kredit_m_status"><?php echo $zx_kredit_m->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $zx_kredit_m->status->CellAttributes() ?>>
<span id="el_zx_kredit_m_status">
<span<?php echo $zx_kredit_m->status->ViewAttributes() ?>>
<?php echo $zx_kredit_m->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->status_lunas->Visible) { // status_lunas ?>
	<tr id="r_status_lunas">
		<td><span id="elh_zx_kredit_m_status_lunas"><?php echo $zx_kredit_m->status_lunas->FldCaption() ?></span></td>
		<td data-name="status_lunas"<?php echo $zx_kredit_m->status_lunas->CellAttributes() ?>>
<span id="el_zx_kredit_m_status_lunas">
<span<?php echo $zx_kredit_m->status_lunas->ViewAttributes() ?>>
<?php echo $zx_kredit_m->status_lunas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->lastupdate_date->Visible) { // lastupdate_date ?>
	<tr id="r_lastupdate_date">
		<td><span id="elh_zx_kredit_m_lastupdate_date"><?php echo $zx_kredit_m->lastupdate_date->FldCaption() ?></span></td>
		<td data-name="lastupdate_date"<?php echo $zx_kredit_m->lastupdate_date->CellAttributes() ?>>
<span id="el_zx_kredit_m_lastupdate_date">
<span<?php echo $zx_kredit_m->lastupdate_date->ViewAttributes() ?>>
<?php echo $zx_kredit_m->lastupdate_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($zx_kredit_m->lastupdate_user->Visible) { // lastupdate_user ?>
	<tr id="r_lastupdate_user">
		<td><span id="elh_zx_kredit_m_lastupdate_user"><?php echo $zx_kredit_m->lastupdate_user->FldCaption() ?></span></td>
		<td data-name="lastupdate_user"<?php echo $zx_kredit_m->lastupdate_user->CellAttributes() ?>>
<span id="el_zx_kredit_m_lastupdate_user">
<span<?php echo $zx_kredit_m->lastupdate_user->ViewAttributes() ?>>
<?php echo $zx_kredit_m->lastupdate_user->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php if ($zx_kredit_m->Export == "") { ?>
<script type="text/javascript">
fzx_kredit_mview.Init();
</script>
<?php } ?>
<?php
$zx_kredit_m_view->ShowPageFooter();
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
$zx_kredit_m_view->Page_Terminate();
?>
