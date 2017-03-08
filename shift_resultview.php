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

$shift_result_view = NULL; // Initialize page object first

class cshift_result_view extends cshift_result {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'shift_result';

	// Page object name
	var $PageObjName = 'shift_result_view';

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
		$KeyUrl = "";
		if (@$_GET["pegawai_id"] <> "") {
			$this->RecKey["pegawai_id"] = $_GET["pegawai_id"];
			$KeyUrl .= "&amp;pegawai_id=" . urlencode($this->RecKey["pegawai_id"]);
		}
		if (@$_GET["tgl_shift"] <> "") {
			$this->RecKey["tgl_shift"] = $_GET["tgl_shift"];
			$KeyUrl .= "&amp;tgl_shift=" . urlencode($this->RecKey["tgl_shift"]);
		}
		if (@$_GET["khusus_lembur"] <> "") {
			$this->RecKey["khusus_lembur"] = $_GET["khusus_lembur"];
			$KeyUrl .= "&amp;khusus_lembur=" . urlencode($this->RecKey["khusus_lembur"]);
		}
		if (@$_GET["khusus_extra"] <> "") {
			$this->RecKey["khusus_extra"] = $_GET["khusus_extra"];
			$KeyUrl .= "&amp;khusus_extra=" . urlencode($this->RecKey["khusus_extra"]);
		}
		if (@$_GET["temp_id_auto"] <> "") {
			$this->RecKey["temp_id_auto"] = $_GET["temp_id_auto"];
			$KeyUrl .= "&amp;temp_id_auto=" . urlencode($this->RecKey["temp_id_auto"]);
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
				$this->Page_Terminate(ew_GetUrl("shift_resultlist.php"));
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
		if (@$_GET["pegawai_id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["pegawai_id"]);
		}
		if (@$_GET["tgl_shift"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["tgl_shift"]);
		}
		if (@$_GET["khusus_lembur"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["khusus_lembur"]);
		}
		if (@$_GET["khusus_extra"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["khusus_extra"]);
		}
		if (@$_GET["temp_id_auto"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["temp_id_auto"]);
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
			if (@$_GET["pegawai_id"] <> "") {
				$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
				$this->RecKey["pegawai_id"] = $this->pegawai_id->QueryStringValue;
			} elseif (@$_POST["pegawai_id"] <> "") {
				$this->pegawai_id->setFormValue($_POST["pegawai_id"]);
				$this->RecKey["pegawai_id"] = $this->pegawai_id->FormValue;
			} else {
				$sReturnUrl = "shift_resultlist.php"; // Return to list
			}
			if (@$_GET["tgl_shift"] <> "") {
				$this->tgl_shift->setQueryStringValue($_GET["tgl_shift"]);
				$this->RecKey["tgl_shift"] = $this->tgl_shift->QueryStringValue;
			} elseif (@$_POST["tgl_shift"] <> "") {
				$this->tgl_shift->setFormValue($_POST["tgl_shift"]);
				$this->RecKey["tgl_shift"] = $this->tgl_shift->FormValue;
			} else {
				$sReturnUrl = "shift_resultlist.php"; // Return to list
			}
			if (@$_GET["khusus_lembur"] <> "") {
				$this->khusus_lembur->setQueryStringValue($_GET["khusus_lembur"]);
				$this->RecKey["khusus_lembur"] = $this->khusus_lembur->QueryStringValue;
			} elseif (@$_POST["khusus_lembur"] <> "") {
				$this->khusus_lembur->setFormValue($_POST["khusus_lembur"]);
				$this->RecKey["khusus_lembur"] = $this->khusus_lembur->FormValue;
			} else {
				$sReturnUrl = "shift_resultlist.php"; // Return to list
			}
			if (@$_GET["khusus_extra"] <> "") {
				$this->khusus_extra->setQueryStringValue($_GET["khusus_extra"]);
				$this->RecKey["khusus_extra"] = $this->khusus_extra->QueryStringValue;
			} elseif (@$_POST["khusus_extra"] <> "") {
				$this->khusus_extra->setFormValue($_POST["khusus_extra"]);
				$this->RecKey["khusus_extra"] = $this->khusus_extra->FormValue;
			} else {
				$sReturnUrl = "shift_resultlist.php"; // Return to list
			}
			if (@$_GET["temp_id_auto"] <> "") {
				$this->temp_id_auto->setQueryStringValue($_GET["temp_id_auto"]);
				$this->RecKey["temp_id_auto"] = $this->temp_id_auto->QueryStringValue;
			} elseif (@$_POST["temp_id_auto"] <> "") {
				$this->temp_id_auto->setFormValue($_POST["temp_id_auto"]);
				$this->RecKey["temp_id_auto"] = $this->temp_id_auto->FormValue;
			} else {
				$sReturnUrl = "shift_resultlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "shift_resultlist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "shift_resultlist.php"; // Not page request, return to list
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

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

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

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_shift_result\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_shift_result',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fshift_resultview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("shift_resultlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($shift_result_view)) $shift_result_view = new cshift_result_view();

// Page init
$shift_result_view->Page_Init();

// Page main
$shift_result_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$shift_result_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($shift_result->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fshift_resultview = new ew_Form("fshift_resultview", "view");

// Form_CustomValidate event
fshift_resultview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fshift_resultview.ValidateRequired = true;
<?php } else { ?>
fshift_resultview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($shift_result->Export == "") { ?>
<div class="ewToolbar">
<?php if (!$shift_result_view->IsModal) { ?>
<?php if ($shift_result->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php } ?>
<?php $shift_result_view->ExportOptions->Render("body") ?>
<?php
	foreach ($shift_result_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$shift_result_view->IsModal) { ?>
<?php if ($shift_result->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $shift_result_view->ShowPageHeader(); ?>
<?php
$shift_result_view->ShowMessage();
?>
<form name="fshift_resultview" id="fshift_resultview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($shift_result_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $shift_result_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="shift_result">
<?php if ($shift_result_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($shift_result->pegawai_id->Visible) { // pegawai_id ?>
	<tr id="r_pegawai_id">
		<td><span id="elh_shift_result_pegawai_id"><?php echo $shift_result->pegawai_id->FldCaption() ?></span></td>
		<td data-name="pegawai_id"<?php echo $shift_result->pegawai_id->CellAttributes() ?>>
<span id="el_shift_result_pegawai_id">
<span<?php echo $shift_result->pegawai_id->ViewAttributes() ?>>
<?php echo $shift_result->pegawai_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->tgl_shift->Visible) { // tgl_shift ?>
	<tr id="r_tgl_shift">
		<td><span id="elh_shift_result_tgl_shift"><?php echo $shift_result->tgl_shift->FldCaption() ?></span></td>
		<td data-name="tgl_shift"<?php echo $shift_result->tgl_shift->CellAttributes() ?>>
<span id="el_shift_result_tgl_shift">
<span<?php echo $shift_result->tgl_shift->ViewAttributes() ?>>
<?php echo $shift_result->tgl_shift->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->khusus_lembur->Visible) { // khusus_lembur ?>
	<tr id="r_khusus_lembur">
		<td><span id="elh_shift_result_khusus_lembur"><?php echo $shift_result->khusus_lembur->FldCaption() ?></span></td>
		<td data-name="khusus_lembur"<?php echo $shift_result->khusus_lembur->CellAttributes() ?>>
<span id="el_shift_result_khusus_lembur">
<span<?php echo $shift_result->khusus_lembur->ViewAttributes() ?>>
<?php echo $shift_result->khusus_lembur->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->khusus_extra->Visible) { // khusus_extra ?>
	<tr id="r_khusus_extra">
		<td><span id="elh_shift_result_khusus_extra"><?php echo $shift_result->khusus_extra->FldCaption() ?></span></td>
		<td data-name="khusus_extra"<?php echo $shift_result->khusus_extra->CellAttributes() ?>>
<span id="el_shift_result_khusus_extra">
<span<?php echo $shift_result->khusus_extra->ViewAttributes() ?>>
<?php echo $shift_result->khusus_extra->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->temp_id_auto->Visible) { // temp_id_auto ?>
	<tr id="r_temp_id_auto">
		<td><span id="elh_shift_result_temp_id_auto"><?php echo $shift_result->temp_id_auto->FldCaption() ?></span></td>
		<td data-name="temp_id_auto"<?php echo $shift_result->temp_id_auto->CellAttributes() ?>>
<span id="el_shift_result_temp_id_auto">
<span<?php echo $shift_result->temp_id_auto->ViewAttributes() ?>>
<?php echo $shift_result->temp_id_auto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->jdw_kerja_m_id->Visible) { // jdw_kerja_m_id ?>
	<tr id="r_jdw_kerja_m_id">
		<td><span id="elh_shift_result_jdw_kerja_m_id"><?php echo $shift_result->jdw_kerja_m_id->FldCaption() ?></span></td>
		<td data-name="jdw_kerja_m_id"<?php echo $shift_result->jdw_kerja_m_id->CellAttributes() ?>>
<span id="el_shift_result_jdw_kerja_m_id">
<span<?php echo $shift_result->jdw_kerja_m_id->ViewAttributes() ?>>
<?php echo $shift_result->jdw_kerja_m_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->jk_id->Visible) { // jk_id ?>
	<tr id="r_jk_id">
		<td><span id="elh_shift_result_jk_id"><?php echo $shift_result->jk_id->FldCaption() ?></span></td>
		<td data-name="jk_id"<?php echo $shift_result->jk_id->CellAttributes() ?>>
<span id="el_shift_result_jk_id">
<span<?php echo $shift_result->jk_id->ViewAttributes() ?>>
<?php echo $shift_result->jk_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->jns_dok->Visible) { // jns_dok ?>
	<tr id="r_jns_dok">
		<td><span id="elh_shift_result_jns_dok"><?php echo $shift_result->jns_dok->FldCaption() ?></span></td>
		<td data-name="jns_dok"<?php echo $shift_result->jns_dok->CellAttributes() ?>>
<span id="el_shift_result_jns_dok">
<span<?php echo $shift_result->jns_dok->ViewAttributes() ?>>
<?php echo $shift_result->jns_dok->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->izin_jenis_id->Visible) { // izin_jenis_id ?>
	<tr id="r_izin_jenis_id">
		<td><span id="elh_shift_result_izin_jenis_id"><?php echo $shift_result->izin_jenis_id->FldCaption() ?></span></td>
		<td data-name="izin_jenis_id"<?php echo $shift_result->izin_jenis_id->CellAttributes() ?>>
<span id="el_shift_result_izin_jenis_id">
<span<?php echo $shift_result->izin_jenis_id->ViewAttributes() ?>>
<?php echo $shift_result->izin_jenis_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->cuti_n_id->Visible) { // cuti_n_id ?>
	<tr id="r_cuti_n_id">
		<td><span id="elh_shift_result_cuti_n_id"><?php echo $shift_result->cuti_n_id->FldCaption() ?></span></td>
		<td data-name="cuti_n_id"<?php echo $shift_result->cuti_n_id->CellAttributes() ?>>
<span id="el_shift_result_cuti_n_id">
<span<?php echo $shift_result->cuti_n_id->ViewAttributes() ?>>
<?php echo $shift_result->cuti_n_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->libur_umum->Visible) { // libur_umum ?>
	<tr id="r_libur_umum">
		<td><span id="elh_shift_result_libur_umum"><?php echo $shift_result->libur_umum->FldCaption() ?></span></td>
		<td data-name="libur_umum"<?php echo $shift_result->libur_umum->CellAttributes() ?>>
<span id="el_shift_result_libur_umum">
<span<?php echo $shift_result->libur_umum->ViewAttributes() ?>>
<?php echo $shift_result->libur_umum->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->libur_rutin->Visible) { // libur_rutin ?>
	<tr id="r_libur_rutin">
		<td><span id="elh_shift_result_libur_rutin"><?php echo $shift_result->libur_rutin->FldCaption() ?></span></td>
		<td data-name="libur_rutin"<?php echo $shift_result->libur_rutin->CellAttributes() ?>>
<span id="el_shift_result_libur_rutin">
<span<?php echo $shift_result->libur_rutin->ViewAttributes() ?>>
<?php echo $shift_result->libur_rutin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->jk_ot->Visible) { // jk_ot ?>
	<tr id="r_jk_ot">
		<td><span id="elh_shift_result_jk_ot"><?php echo $shift_result->jk_ot->FldCaption() ?></span></td>
		<td data-name="jk_ot"<?php echo $shift_result->jk_ot->CellAttributes() ?>>
<span id="el_shift_result_jk_ot">
<span<?php echo $shift_result->jk_ot->ViewAttributes() ?>>
<?php echo $shift_result->jk_ot->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->scan_in->Visible) { // scan_in ?>
	<tr id="r_scan_in">
		<td><span id="elh_shift_result_scan_in"><?php echo $shift_result->scan_in->FldCaption() ?></span></td>
		<td data-name="scan_in"<?php echo $shift_result->scan_in->CellAttributes() ?>>
<span id="el_shift_result_scan_in">
<span<?php echo $shift_result->scan_in->ViewAttributes() ?>>
<?php echo $shift_result->scan_in->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->att_id_in->Visible) { // att_id_in ?>
	<tr id="r_att_id_in">
		<td><span id="elh_shift_result_att_id_in"><?php echo $shift_result->att_id_in->FldCaption() ?></span></td>
		<td data-name="att_id_in"<?php echo $shift_result->att_id_in->CellAttributes() ?>>
<span id="el_shift_result_att_id_in">
<span<?php echo $shift_result->att_id_in->ViewAttributes() ?>>
<?php echo $shift_result->att_id_in->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->late_permission->Visible) { // late_permission ?>
	<tr id="r_late_permission">
		<td><span id="elh_shift_result_late_permission"><?php echo $shift_result->late_permission->FldCaption() ?></span></td>
		<td data-name="late_permission"<?php echo $shift_result->late_permission->CellAttributes() ?>>
<span id="el_shift_result_late_permission">
<span<?php echo $shift_result->late_permission->ViewAttributes() ?>>
<?php echo $shift_result->late_permission->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->late_minute->Visible) { // late_minute ?>
	<tr id="r_late_minute">
		<td><span id="elh_shift_result_late_minute"><?php echo $shift_result->late_minute->FldCaption() ?></span></td>
		<td data-name="late_minute"<?php echo $shift_result->late_minute->CellAttributes() ?>>
<span id="el_shift_result_late_minute">
<span<?php echo $shift_result->late_minute->ViewAttributes() ?>>
<?php echo $shift_result->late_minute->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->late->Visible) { // late ?>
	<tr id="r_late">
		<td><span id="elh_shift_result_late"><?php echo $shift_result->late->FldCaption() ?></span></td>
		<td data-name="late"<?php echo $shift_result->late->CellAttributes() ?>>
<span id="el_shift_result_late">
<span<?php echo $shift_result->late->ViewAttributes() ?>>
<?php echo $shift_result->late->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->break_out->Visible) { // break_out ?>
	<tr id="r_break_out">
		<td><span id="elh_shift_result_break_out"><?php echo $shift_result->break_out->FldCaption() ?></span></td>
		<td data-name="break_out"<?php echo $shift_result->break_out->CellAttributes() ?>>
<span id="el_shift_result_break_out">
<span<?php echo $shift_result->break_out->ViewAttributes() ?>>
<?php echo $shift_result->break_out->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->att_id_break1->Visible) { // att_id_break1 ?>
	<tr id="r_att_id_break1">
		<td><span id="elh_shift_result_att_id_break1"><?php echo $shift_result->att_id_break1->FldCaption() ?></span></td>
		<td data-name="att_id_break1"<?php echo $shift_result->att_id_break1->CellAttributes() ?>>
<span id="el_shift_result_att_id_break1">
<span<?php echo $shift_result->att_id_break1->ViewAttributes() ?>>
<?php echo $shift_result->att_id_break1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->break_in->Visible) { // break_in ?>
	<tr id="r_break_in">
		<td><span id="elh_shift_result_break_in"><?php echo $shift_result->break_in->FldCaption() ?></span></td>
		<td data-name="break_in"<?php echo $shift_result->break_in->CellAttributes() ?>>
<span id="el_shift_result_break_in">
<span<?php echo $shift_result->break_in->ViewAttributes() ?>>
<?php echo $shift_result->break_in->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->att_id_break2->Visible) { // att_id_break2 ?>
	<tr id="r_att_id_break2">
		<td><span id="elh_shift_result_att_id_break2"><?php echo $shift_result->att_id_break2->FldCaption() ?></span></td>
		<td data-name="att_id_break2"<?php echo $shift_result->att_id_break2->CellAttributes() ?>>
<span id="el_shift_result_att_id_break2">
<span<?php echo $shift_result->att_id_break2->ViewAttributes() ?>>
<?php echo $shift_result->att_id_break2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->break_minute->Visible) { // break_minute ?>
	<tr id="r_break_minute">
		<td><span id="elh_shift_result_break_minute"><?php echo $shift_result->break_minute->FldCaption() ?></span></td>
		<td data-name="break_minute"<?php echo $shift_result->break_minute->CellAttributes() ?>>
<span id="el_shift_result_break_minute">
<span<?php echo $shift_result->break_minute->ViewAttributes() ?>>
<?php echo $shift_result->break_minute->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->break->Visible) { // break ?>
	<tr id="r_break">
		<td><span id="elh_shift_result_break"><?php echo $shift_result->break->FldCaption() ?></span></td>
		<td data-name="break"<?php echo $shift_result->break->CellAttributes() ?>>
<span id="el_shift_result_break">
<span<?php echo $shift_result->break->ViewAttributes() ?>>
<?php echo $shift_result->break->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->break_ot_minute->Visible) { // break_ot_minute ?>
	<tr id="r_break_ot_minute">
		<td><span id="elh_shift_result_break_ot_minute"><?php echo $shift_result->break_ot_minute->FldCaption() ?></span></td>
		<td data-name="break_ot_minute"<?php echo $shift_result->break_ot_minute->CellAttributes() ?>>
<span id="el_shift_result_break_ot_minute">
<span<?php echo $shift_result->break_ot_minute->ViewAttributes() ?>>
<?php echo $shift_result->break_ot_minute->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->break_ot->Visible) { // break_ot ?>
	<tr id="r_break_ot">
		<td><span id="elh_shift_result_break_ot"><?php echo $shift_result->break_ot->FldCaption() ?></span></td>
		<td data-name="break_ot"<?php echo $shift_result->break_ot->CellAttributes() ?>>
<span id="el_shift_result_break_ot">
<span<?php echo $shift_result->break_ot->ViewAttributes() ?>>
<?php echo $shift_result->break_ot->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->early_permission->Visible) { // early_permission ?>
	<tr id="r_early_permission">
		<td><span id="elh_shift_result_early_permission"><?php echo $shift_result->early_permission->FldCaption() ?></span></td>
		<td data-name="early_permission"<?php echo $shift_result->early_permission->CellAttributes() ?>>
<span id="el_shift_result_early_permission">
<span<?php echo $shift_result->early_permission->ViewAttributes() ?>>
<?php echo $shift_result->early_permission->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->early_minute->Visible) { // early_minute ?>
	<tr id="r_early_minute">
		<td><span id="elh_shift_result_early_minute"><?php echo $shift_result->early_minute->FldCaption() ?></span></td>
		<td data-name="early_minute"<?php echo $shift_result->early_minute->CellAttributes() ?>>
<span id="el_shift_result_early_minute">
<span<?php echo $shift_result->early_minute->ViewAttributes() ?>>
<?php echo $shift_result->early_minute->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->early->Visible) { // early ?>
	<tr id="r_early">
		<td><span id="elh_shift_result_early"><?php echo $shift_result->early->FldCaption() ?></span></td>
		<td data-name="early"<?php echo $shift_result->early->CellAttributes() ?>>
<span id="el_shift_result_early">
<span<?php echo $shift_result->early->ViewAttributes() ?>>
<?php echo $shift_result->early->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->scan_out->Visible) { // scan_out ?>
	<tr id="r_scan_out">
		<td><span id="elh_shift_result_scan_out"><?php echo $shift_result->scan_out->FldCaption() ?></span></td>
		<td data-name="scan_out"<?php echo $shift_result->scan_out->CellAttributes() ?>>
<span id="el_shift_result_scan_out">
<span<?php echo $shift_result->scan_out->ViewAttributes() ?>>
<?php echo $shift_result->scan_out->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->att_id_out->Visible) { // att_id_out ?>
	<tr id="r_att_id_out">
		<td><span id="elh_shift_result_att_id_out"><?php echo $shift_result->att_id_out->FldCaption() ?></span></td>
		<td data-name="att_id_out"<?php echo $shift_result->att_id_out->CellAttributes() ?>>
<span id="el_shift_result_att_id_out">
<span<?php echo $shift_result->att_id_out->ViewAttributes() ?>>
<?php echo $shift_result->att_id_out->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->durasi_minute->Visible) { // durasi_minute ?>
	<tr id="r_durasi_minute">
		<td><span id="elh_shift_result_durasi_minute"><?php echo $shift_result->durasi_minute->FldCaption() ?></span></td>
		<td data-name="durasi_minute"<?php echo $shift_result->durasi_minute->CellAttributes() ?>>
<span id="el_shift_result_durasi_minute">
<span<?php echo $shift_result->durasi_minute->ViewAttributes() ?>>
<?php echo $shift_result->durasi_minute->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->durasi->Visible) { // durasi ?>
	<tr id="r_durasi">
		<td><span id="elh_shift_result_durasi"><?php echo $shift_result->durasi->FldCaption() ?></span></td>
		<td data-name="durasi"<?php echo $shift_result->durasi->CellAttributes() ?>>
<span id="el_shift_result_durasi">
<span<?php echo $shift_result->durasi->ViewAttributes() ?>>
<?php echo $shift_result->durasi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->durasi_eot_minute->Visible) { // durasi_eot_minute ?>
	<tr id="r_durasi_eot_minute">
		<td><span id="elh_shift_result_durasi_eot_minute"><?php echo $shift_result->durasi_eot_minute->FldCaption() ?></span></td>
		<td data-name="durasi_eot_minute"<?php echo $shift_result->durasi_eot_minute->CellAttributes() ?>>
<span id="el_shift_result_durasi_eot_minute">
<span<?php echo $shift_result->durasi_eot_minute->ViewAttributes() ?>>
<?php echo $shift_result->durasi_eot_minute->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->jk_count_as->Visible) { // jk_count_as ?>
	<tr id="r_jk_count_as">
		<td><span id="elh_shift_result_jk_count_as"><?php echo $shift_result->jk_count_as->FldCaption() ?></span></td>
		<td data-name="jk_count_as"<?php echo $shift_result->jk_count_as->CellAttributes() ?>>
<span id="el_shift_result_jk_count_as">
<span<?php echo $shift_result->jk_count_as->ViewAttributes() ?>>
<?php echo $shift_result->jk_count_as->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->status_jk->Visible) { // status_jk ?>
	<tr id="r_status_jk">
		<td><span id="elh_shift_result_status_jk"><?php echo $shift_result->status_jk->FldCaption() ?></span></td>
		<td data-name="status_jk"<?php echo $shift_result->status_jk->CellAttributes() ?>>
<span id="el_shift_result_status_jk">
<span<?php echo $shift_result->status_jk->ViewAttributes() ?>>
<?php echo $shift_result->status_jk->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($shift_result->keterangan->Visible) { // keterangan ?>
	<tr id="r_keterangan">
		<td><span id="elh_shift_result_keterangan"><?php echo $shift_result->keterangan->FldCaption() ?></span></td>
		<td data-name="keterangan"<?php echo $shift_result->keterangan->CellAttributes() ?>>
<span id="el_shift_result_keterangan">
<span<?php echo $shift_result->keterangan->ViewAttributes() ?>>
<?php echo $shift_result->keterangan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php if ($shift_result->Export == "") { ?>
<script type="text/javascript">
fshift_resultview.Init();
</script>
<?php } ?>
<?php
$shift_result_view->ShowPageFooter();
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
$shift_result_view->Page_Terminate();
?>
