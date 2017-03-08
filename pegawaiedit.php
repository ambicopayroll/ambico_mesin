<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "pegawaiinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$pegawai_edit = NULL; // Initialize page object first

class cpegawai_edit extends cpegawai {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'pegawai';

	// Page object name
	var $PageObjName = 'pegawai_edit';

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

		// Table object (pegawai)
		if (!isset($GLOBALS["pegawai"]) || get_class($GLOBALS["pegawai"]) == "cpegawai") {
			$GLOBALS["pegawai"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pegawai"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pegawai', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("pegawailist.php"));
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
		$this->pegawai_id->SetVisibility();
		$this->pegawai_pin->SetVisibility();
		$this->pegawai_nip->SetVisibility();
		$this->pegawai_nama->SetVisibility();
		$this->pegawai_pwd->SetVisibility();
		$this->pegawai_rfid->SetVisibility();
		$this->pegawai_privilege->SetVisibility();
		$this->pegawai_telp->SetVisibility();
		$this->pegawai_status->SetVisibility();
		$this->tempat_lahir->SetVisibility();
		$this->tgl_lahir->SetVisibility();
		$this->pembagian1_id->SetVisibility();
		$this->pembagian2_id->SetVisibility();
		$this->pembagian3_id->SetVisibility();
		$this->tgl_mulai_kerja->SetVisibility();
		$this->tgl_resign->SetVisibility();
		$this->gender->SetVisibility();
		$this->tgl_masuk_pertama->SetVisibility();
		$this->photo_path->SetVisibility();
		$this->tmp_img->SetVisibility();
		$this->nama_bank->SetVisibility();
		$this->nama_rek->SetVisibility();
		$this->no_rek->SetVisibility();

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
		global $EW_EXPORT, $pegawai;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pegawai);
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

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

		// Load key from QueryString
		if (@$_GET["pegawai_id"] <> "") {
			$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->pegawai_id->CurrentValue == "") {
			$this->Page_Terminate("pegawailist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("pegawailist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "pegawailist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
		if (!$this->pegawai_pin->FldIsDetailKey) {
			$this->pegawai_pin->setFormValue($objForm->GetValue("x_pegawai_pin"));
		}
		if (!$this->pegawai_nip->FldIsDetailKey) {
			$this->pegawai_nip->setFormValue($objForm->GetValue("x_pegawai_nip"));
		}
		if (!$this->pegawai_nama->FldIsDetailKey) {
			$this->pegawai_nama->setFormValue($objForm->GetValue("x_pegawai_nama"));
		}
		if (!$this->pegawai_pwd->FldIsDetailKey) {
			$this->pegawai_pwd->setFormValue($objForm->GetValue("x_pegawai_pwd"));
		}
		if (!$this->pegawai_rfid->FldIsDetailKey) {
			$this->pegawai_rfid->setFormValue($objForm->GetValue("x_pegawai_rfid"));
		}
		if (!$this->pegawai_privilege->FldIsDetailKey) {
			$this->pegawai_privilege->setFormValue($objForm->GetValue("x_pegawai_privilege"));
		}
		if (!$this->pegawai_telp->FldIsDetailKey) {
			$this->pegawai_telp->setFormValue($objForm->GetValue("x_pegawai_telp"));
		}
		if (!$this->pegawai_status->FldIsDetailKey) {
			$this->pegawai_status->setFormValue($objForm->GetValue("x_pegawai_status"));
		}
		if (!$this->tempat_lahir->FldIsDetailKey) {
			$this->tempat_lahir->setFormValue($objForm->GetValue("x_tempat_lahir"));
		}
		if (!$this->tgl_lahir->FldIsDetailKey) {
			$this->tgl_lahir->setFormValue($objForm->GetValue("x_tgl_lahir"));
			$this->tgl_lahir->CurrentValue = ew_UnFormatDateTime($this->tgl_lahir->CurrentValue, 0);
		}
		if (!$this->pembagian1_id->FldIsDetailKey) {
			$this->pembagian1_id->setFormValue($objForm->GetValue("x_pembagian1_id"));
		}
		if (!$this->pembagian2_id->FldIsDetailKey) {
			$this->pembagian2_id->setFormValue($objForm->GetValue("x_pembagian2_id"));
		}
		if (!$this->pembagian3_id->FldIsDetailKey) {
			$this->pembagian3_id->setFormValue($objForm->GetValue("x_pembagian3_id"));
		}
		if (!$this->tgl_mulai_kerja->FldIsDetailKey) {
			$this->tgl_mulai_kerja->setFormValue($objForm->GetValue("x_tgl_mulai_kerja"));
			$this->tgl_mulai_kerja->CurrentValue = ew_UnFormatDateTime($this->tgl_mulai_kerja->CurrentValue, 0);
		}
		if (!$this->tgl_resign->FldIsDetailKey) {
			$this->tgl_resign->setFormValue($objForm->GetValue("x_tgl_resign"));
			$this->tgl_resign->CurrentValue = ew_UnFormatDateTime($this->tgl_resign->CurrentValue, 0);
		}
		if (!$this->gender->FldIsDetailKey) {
			$this->gender->setFormValue($objForm->GetValue("x_gender"));
		}
		if (!$this->tgl_masuk_pertama->FldIsDetailKey) {
			$this->tgl_masuk_pertama->setFormValue($objForm->GetValue("x_tgl_masuk_pertama"));
			$this->tgl_masuk_pertama->CurrentValue = ew_UnFormatDateTime($this->tgl_masuk_pertama->CurrentValue, 0);
		}
		if (!$this->photo_path->FldIsDetailKey) {
			$this->photo_path->setFormValue($objForm->GetValue("x_photo_path"));
		}
		if (!$this->tmp_img->FldIsDetailKey) {
			$this->tmp_img->setFormValue($objForm->GetValue("x_tmp_img"));
		}
		if (!$this->nama_bank->FldIsDetailKey) {
			$this->nama_bank->setFormValue($objForm->GetValue("x_nama_bank"));
		}
		if (!$this->nama_rek->FldIsDetailKey) {
			$this->nama_rek->setFormValue($objForm->GetValue("x_nama_rek"));
		}
		if (!$this->no_rek->FldIsDetailKey) {
			$this->no_rek->setFormValue($objForm->GetValue("x_no_rek"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->pegawai_pin->CurrentValue = $this->pegawai_pin->FormValue;
		$this->pegawai_nip->CurrentValue = $this->pegawai_nip->FormValue;
		$this->pegawai_nama->CurrentValue = $this->pegawai_nama->FormValue;
		$this->pegawai_pwd->CurrentValue = $this->pegawai_pwd->FormValue;
		$this->pegawai_rfid->CurrentValue = $this->pegawai_rfid->FormValue;
		$this->pegawai_privilege->CurrentValue = $this->pegawai_privilege->FormValue;
		$this->pegawai_telp->CurrentValue = $this->pegawai_telp->FormValue;
		$this->pegawai_status->CurrentValue = $this->pegawai_status->FormValue;
		$this->tempat_lahir->CurrentValue = $this->tempat_lahir->FormValue;
		$this->tgl_lahir->CurrentValue = $this->tgl_lahir->FormValue;
		$this->tgl_lahir->CurrentValue = ew_UnFormatDateTime($this->tgl_lahir->CurrentValue, 0);
		$this->pembagian1_id->CurrentValue = $this->pembagian1_id->FormValue;
		$this->pembagian2_id->CurrentValue = $this->pembagian2_id->FormValue;
		$this->pembagian3_id->CurrentValue = $this->pembagian3_id->FormValue;
		$this->tgl_mulai_kerja->CurrentValue = $this->tgl_mulai_kerja->FormValue;
		$this->tgl_mulai_kerja->CurrentValue = ew_UnFormatDateTime($this->tgl_mulai_kerja->CurrentValue, 0);
		$this->tgl_resign->CurrentValue = $this->tgl_resign->FormValue;
		$this->tgl_resign->CurrentValue = ew_UnFormatDateTime($this->tgl_resign->CurrentValue, 0);
		$this->gender->CurrentValue = $this->gender->FormValue;
		$this->tgl_masuk_pertama->CurrentValue = $this->tgl_masuk_pertama->FormValue;
		$this->tgl_masuk_pertama->CurrentValue = ew_UnFormatDateTime($this->tgl_masuk_pertama->CurrentValue, 0);
		$this->photo_path->CurrentValue = $this->photo_path->FormValue;
		$this->tmp_img->CurrentValue = $this->tmp_img->FormValue;
		$this->nama_bank->CurrentValue = $this->nama_bank->FormValue;
		$this->nama_rek->CurrentValue = $this->nama_rek->FormValue;
		$this->no_rek->CurrentValue = $this->no_rek->FormValue;
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
		$this->pegawai_pin->setDbValue($rs->fields('pegawai_pin'));
		$this->pegawai_nip->setDbValue($rs->fields('pegawai_nip'));
		$this->pegawai_nama->setDbValue($rs->fields('pegawai_nama'));
		$this->pegawai_pwd->setDbValue($rs->fields('pegawai_pwd'));
		$this->pegawai_rfid->setDbValue($rs->fields('pegawai_rfid'));
		$this->pegawai_privilege->setDbValue($rs->fields('pegawai_privilege'));
		$this->pegawai_telp->setDbValue($rs->fields('pegawai_telp'));
		$this->pegawai_status->setDbValue($rs->fields('pegawai_status'));
		$this->tempat_lahir->setDbValue($rs->fields('tempat_lahir'));
		$this->tgl_lahir->setDbValue($rs->fields('tgl_lahir'));
		$this->pembagian1_id->setDbValue($rs->fields('pembagian1_id'));
		$this->pembagian2_id->setDbValue($rs->fields('pembagian2_id'));
		$this->pembagian3_id->setDbValue($rs->fields('pembagian3_id'));
		$this->tgl_mulai_kerja->setDbValue($rs->fields('tgl_mulai_kerja'));
		$this->tgl_resign->setDbValue($rs->fields('tgl_resign'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->tgl_masuk_pertama->setDbValue($rs->fields('tgl_masuk_pertama'));
		$this->photo_path->setDbValue($rs->fields('photo_path'));
		$this->tmp_img->setDbValue($rs->fields('tmp_img'));
		$this->nama_bank->setDbValue($rs->fields('nama_bank'));
		$this->nama_rek->setDbValue($rs->fields('nama_rek'));
		$this->no_rek->setDbValue($rs->fields('no_rek'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->pegawai_pin->DbValue = $row['pegawai_pin'];
		$this->pegawai_nip->DbValue = $row['pegawai_nip'];
		$this->pegawai_nama->DbValue = $row['pegawai_nama'];
		$this->pegawai_pwd->DbValue = $row['pegawai_pwd'];
		$this->pegawai_rfid->DbValue = $row['pegawai_rfid'];
		$this->pegawai_privilege->DbValue = $row['pegawai_privilege'];
		$this->pegawai_telp->DbValue = $row['pegawai_telp'];
		$this->pegawai_status->DbValue = $row['pegawai_status'];
		$this->tempat_lahir->DbValue = $row['tempat_lahir'];
		$this->tgl_lahir->DbValue = $row['tgl_lahir'];
		$this->pembagian1_id->DbValue = $row['pembagian1_id'];
		$this->pembagian2_id->DbValue = $row['pembagian2_id'];
		$this->pembagian3_id->DbValue = $row['pembagian3_id'];
		$this->tgl_mulai_kerja->DbValue = $row['tgl_mulai_kerja'];
		$this->tgl_resign->DbValue = $row['tgl_resign'];
		$this->gender->DbValue = $row['gender'];
		$this->tgl_masuk_pertama->DbValue = $row['tgl_masuk_pertama'];
		$this->photo_path->DbValue = $row['photo_path'];
		$this->tmp_img->DbValue = $row['tmp_img'];
		$this->nama_bank->DbValue = $row['nama_bank'];
		$this->nama_rek->DbValue = $row['nama_rek'];
		$this->no_rek->DbValue = $row['no_rek'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// pegawai_id
		// pegawai_pin
		// pegawai_nip
		// pegawai_nama
		// pegawai_pwd
		// pegawai_rfid
		// pegawai_privilege
		// pegawai_telp
		// pegawai_status
		// tempat_lahir
		// tgl_lahir
		// pembagian1_id
		// pembagian2_id
		// pembagian3_id
		// tgl_mulai_kerja
		// tgl_resign
		// gender
		// tgl_masuk_pertama
		// photo_path
		// tmp_img
		// nama_bank
		// nama_rek
		// no_rek

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// pegawai_pin
		$this->pegawai_pin->ViewValue = $this->pegawai_pin->CurrentValue;
		$this->pegawai_pin->ViewCustomAttributes = "";

		// pegawai_nip
		$this->pegawai_nip->ViewValue = $this->pegawai_nip->CurrentValue;
		$this->pegawai_nip->ViewCustomAttributes = "";

		// pegawai_nama
		$this->pegawai_nama->ViewValue = $this->pegawai_nama->CurrentValue;
		$this->pegawai_nama->ViewCustomAttributes = "";

		// pegawai_pwd
		$this->pegawai_pwd->ViewValue = $this->pegawai_pwd->CurrentValue;
		$this->pegawai_pwd->ViewCustomAttributes = "";

		// pegawai_rfid
		$this->pegawai_rfid->ViewValue = $this->pegawai_rfid->CurrentValue;
		$this->pegawai_rfid->ViewCustomAttributes = "";

		// pegawai_privilege
		$this->pegawai_privilege->ViewValue = $this->pegawai_privilege->CurrentValue;
		$this->pegawai_privilege->ViewCustomAttributes = "";

		// pegawai_telp
		$this->pegawai_telp->ViewValue = $this->pegawai_telp->CurrentValue;
		$this->pegawai_telp->ViewCustomAttributes = "";

		// pegawai_status
		$this->pegawai_status->ViewValue = $this->pegawai_status->CurrentValue;
		$this->pegawai_status->ViewCustomAttributes = "";

		// tempat_lahir
		$this->tempat_lahir->ViewValue = $this->tempat_lahir->CurrentValue;
		$this->tempat_lahir->ViewCustomAttributes = "";

		// tgl_lahir
		$this->tgl_lahir->ViewValue = $this->tgl_lahir->CurrentValue;
		$this->tgl_lahir->ViewValue = ew_FormatDateTime($this->tgl_lahir->ViewValue, 0);
		$this->tgl_lahir->ViewCustomAttributes = "";

		// pembagian1_id
		$this->pembagian1_id->ViewValue = $this->pembagian1_id->CurrentValue;
		$this->pembagian1_id->ViewCustomAttributes = "";

		// pembagian2_id
		$this->pembagian2_id->ViewValue = $this->pembagian2_id->CurrentValue;
		$this->pembagian2_id->ViewCustomAttributes = "";

		// pembagian3_id
		$this->pembagian3_id->ViewValue = $this->pembagian3_id->CurrentValue;
		$this->pembagian3_id->ViewCustomAttributes = "";

		// tgl_mulai_kerja
		$this->tgl_mulai_kerja->ViewValue = $this->tgl_mulai_kerja->CurrentValue;
		$this->tgl_mulai_kerja->ViewValue = ew_FormatDateTime($this->tgl_mulai_kerja->ViewValue, 0);
		$this->tgl_mulai_kerja->ViewCustomAttributes = "";

		// tgl_resign
		$this->tgl_resign->ViewValue = $this->tgl_resign->CurrentValue;
		$this->tgl_resign->ViewValue = ew_FormatDateTime($this->tgl_resign->ViewValue, 0);
		$this->tgl_resign->ViewCustomAttributes = "";

		// gender
		$this->gender->ViewValue = $this->gender->CurrentValue;
		$this->gender->ViewCustomAttributes = "";

		// tgl_masuk_pertama
		$this->tgl_masuk_pertama->ViewValue = $this->tgl_masuk_pertama->CurrentValue;
		$this->tgl_masuk_pertama->ViewValue = ew_FormatDateTime($this->tgl_masuk_pertama->ViewValue, 0);
		$this->tgl_masuk_pertama->ViewCustomAttributes = "";

		// photo_path
		$this->photo_path->ViewValue = $this->photo_path->CurrentValue;
		$this->photo_path->ViewCustomAttributes = "";

		// tmp_img
		$this->tmp_img->ViewValue = $this->tmp_img->CurrentValue;
		$this->tmp_img->ViewCustomAttributes = "";

		// nama_bank
		$this->nama_bank->ViewValue = $this->nama_bank->CurrentValue;
		$this->nama_bank->ViewCustomAttributes = "";

		// nama_rek
		$this->nama_rek->ViewValue = $this->nama_rek->CurrentValue;
		$this->nama_rek->ViewCustomAttributes = "";

		// no_rek
		$this->no_rek->ViewValue = $this->no_rek->CurrentValue;
		$this->no_rek->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

			// pegawai_pin
			$this->pegawai_pin->LinkCustomAttributes = "";
			$this->pegawai_pin->HrefValue = "";
			$this->pegawai_pin->TooltipValue = "";

			// pegawai_nip
			$this->pegawai_nip->LinkCustomAttributes = "";
			$this->pegawai_nip->HrefValue = "";
			$this->pegawai_nip->TooltipValue = "";

			// pegawai_nama
			$this->pegawai_nama->LinkCustomAttributes = "";
			$this->pegawai_nama->HrefValue = "";
			$this->pegawai_nama->TooltipValue = "";

			// pegawai_pwd
			$this->pegawai_pwd->LinkCustomAttributes = "";
			$this->pegawai_pwd->HrefValue = "";
			$this->pegawai_pwd->TooltipValue = "";

			// pegawai_rfid
			$this->pegawai_rfid->LinkCustomAttributes = "";
			$this->pegawai_rfid->HrefValue = "";
			$this->pegawai_rfid->TooltipValue = "";

			// pegawai_privilege
			$this->pegawai_privilege->LinkCustomAttributes = "";
			$this->pegawai_privilege->HrefValue = "";
			$this->pegawai_privilege->TooltipValue = "";

			// pegawai_telp
			$this->pegawai_telp->LinkCustomAttributes = "";
			$this->pegawai_telp->HrefValue = "";
			$this->pegawai_telp->TooltipValue = "";

			// pegawai_status
			$this->pegawai_status->LinkCustomAttributes = "";
			$this->pegawai_status->HrefValue = "";
			$this->pegawai_status->TooltipValue = "";

			// tempat_lahir
			$this->tempat_lahir->LinkCustomAttributes = "";
			$this->tempat_lahir->HrefValue = "";
			$this->tempat_lahir->TooltipValue = "";

			// tgl_lahir
			$this->tgl_lahir->LinkCustomAttributes = "";
			$this->tgl_lahir->HrefValue = "";
			$this->tgl_lahir->TooltipValue = "";

			// pembagian1_id
			$this->pembagian1_id->LinkCustomAttributes = "";
			$this->pembagian1_id->HrefValue = "";
			$this->pembagian1_id->TooltipValue = "";

			// pembagian2_id
			$this->pembagian2_id->LinkCustomAttributes = "";
			$this->pembagian2_id->HrefValue = "";
			$this->pembagian2_id->TooltipValue = "";

			// pembagian3_id
			$this->pembagian3_id->LinkCustomAttributes = "";
			$this->pembagian3_id->HrefValue = "";
			$this->pembagian3_id->TooltipValue = "";

			// tgl_mulai_kerja
			$this->tgl_mulai_kerja->LinkCustomAttributes = "";
			$this->tgl_mulai_kerja->HrefValue = "";
			$this->tgl_mulai_kerja->TooltipValue = "";

			// tgl_resign
			$this->tgl_resign->LinkCustomAttributes = "";
			$this->tgl_resign->HrefValue = "";
			$this->tgl_resign->TooltipValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";
			$this->gender->TooltipValue = "";

			// tgl_masuk_pertama
			$this->tgl_masuk_pertama->LinkCustomAttributes = "";
			$this->tgl_masuk_pertama->HrefValue = "";
			$this->tgl_masuk_pertama->TooltipValue = "";

			// photo_path
			$this->photo_path->LinkCustomAttributes = "";
			$this->photo_path->HrefValue = "";
			$this->photo_path->TooltipValue = "";

			// tmp_img
			$this->tmp_img->LinkCustomAttributes = "";
			$this->tmp_img->HrefValue = "";
			$this->tmp_img->TooltipValue = "";

			// nama_bank
			$this->nama_bank->LinkCustomAttributes = "";
			$this->nama_bank->HrefValue = "";
			$this->nama_bank->TooltipValue = "";

			// nama_rek
			$this->nama_rek->LinkCustomAttributes = "";
			$this->nama_rek->HrefValue = "";
			$this->nama_rek->TooltipValue = "";

			// no_rek
			$this->no_rek->LinkCustomAttributes = "";
			$this->no_rek->HrefValue = "";
			$this->no_rek->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// pegawai_id
			$this->pegawai_id->EditAttrs["class"] = "form-control";
			$this->pegawai_id->EditCustomAttributes = "";
			$this->pegawai_id->EditValue = $this->pegawai_id->CurrentValue;
			$this->pegawai_id->ViewCustomAttributes = "";

			// pegawai_pin
			$this->pegawai_pin->EditAttrs["class"] = "form-control";
			$this->pegawai_pin->EditCustomAttributes = "";
			$this->pegawai_pin->EditValue = ew_HtmlEncode($this->pegawai_pin->CurrentValue);
			$this->pegawai_pin->PlaceHolder = ew_RemoveHtml($this->pegawai_pin->FldCaption());

			// pegawai_nip
			$this->pegawai_nip->EditAttrs["class"] = "form-control";
			$this->pegawai_nip->EditCustomAttributes = "";
			$this->pegawai_nip->EditValue = ew_HtmlEncode($this->pegawai_nip->CurrentValue);
			$this->pegawai_nip->PlaceHolder = ew_RemoveHtml($this->pegawai_nip->FldCaption());

			// pegawai_nama
			$this->pegawai_nama->EditAttrs["class"] = "form-control";
			$this->pegawai_nama->EditCustomAttributes = "";
			$this->pegawai_nama->EditValue = ew_HtmlEncode($this->pegawai_nama->CurrentValue);
			$this->pegawai_nama->PlaceHolder = ew_RemoveHtml($this->pegawai_nama->FldCaption());

			// pegawai_pwd
			$this->pegawai_pwd->EditAttrs["class"] = "form-control";
			$this->pegawai_pwd->EditCustomAttributes = "";
			$this->pegawai_pwd->EditValue = ew_HtmlEncode($this->pegawai_pwd->CurrentValue);
			$this->pegawai_pwd->PlaceHolder = ew_RemoveHtml($this->pegawai_pwd->FldCaption());

			// pegawai_rfid
			$this->pegawai_rfid->EditAttrs["class"] = "form-control";
			$this->pegawai_rfid->EditCustomAttributes = "";
			$this->pegawai_rfid->EditValue = ew_HtmlEncode($this->pegawai_rfid->CurrentValue);
			$this->pegawai_rfid->PlaceHolder = ew_RemoveHtml($this->pegawai_rfid->FldCaption());

			// pegawai_privilege
			$this->pegawai_privilege->EditAttrs["class"] = "form-control";
			$this->pegawai_privilege->EditCustomAttributes = "";
			$this->pegawai_privilege->EditValue = ew_HtmlEncode($this->pegawai_privilege->CurrentValue);
			$this->pegawai_privilege->PlaceHolder = ew_RemoveHtml($this->pegawai_privilege->FldCaption());

			// pegawai_telp
			$this->pegawai_telp->EditAttrs["class"] = "form-control";
			$this->pegawai_telp->EditCustomAttributes = "";
			$this->pegawai_telp->EditValue = ew_HtmlEncode($this->pegawai_telp->CurrentValue);
			$this->pegawai_telp->PlaceHolder = ew_RemoveHtml($this->pegawai_telp->FldCaption());

			// pegawai_status
			$this->pegawai_status->EditAttrs["class"] = "form-control";
			$this->pegawai_status->EditCustomAttributes = "";
			$this->pegawai_status->EditValue = ew_HtmlEncode($this->pegawai_status->CurrentValue);
			$this->pegawai_status->PlaceHolder = ew_RemoveHtml($this->pegawai_status->FldCaption());

			// tempat_lahir
			$this->tempat_lahir->EditAttrs["class"] = "form-control";
			$this->tempat_lahir->EditCustomAttributes = "";
			$this->tempat_lahir->EditValue = ew_HtmlEncode($this->tempat_lahir->CurrentValue);
			$this->tempat_lahir->PlaceHolder = ew_RemoveHtml($this->tempat_lahir->FldCaption());

			// tgl_lahir
			$this->tgl_lahir->EditAttrs["class"] = "form-control";
			$this->tgl_lahir->EditCustomAttributes = "";
			$this->tgl_lahir->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_lahir->CurrentValue, 8));
			$this->tgl_lahir->PlaceHolder = ew_RemoveHtml($this->tgl_lahir->FldCaption());

			// pembagian1_id
			$this->pembagian1_id->EditAttrs["class"] = "form-control";
			$this->pembagian1_id->EditCustomAttributes = "";
			$this->pembagian1_id->EditValue = ew_HtmlEncode($this->pembagian1_id->CurrentValue);
			$this->pembagian1_id->PlaceHolder = ew_RemoveHtml($this->pembagian1_id->FldCaption());

			// pembagian2_id
			$this->pembagian2_id->EditAttrs["class"] = "form-control";
			$this->pembagian2_id->EditCustomAttributes = "";
			$this->pembagian2_id->EditValue = ew_HtmlEncode($this->pembagian2_id->CurrentValue);
			$this->pembagian2_id->PlaceHolder = ew_RemoveHtml($this->pembagian2_id->FldCaption());

			// pembagian3_id
			$this->pembagian3_id->EditAttrs["class"] = "form-control";
			$this->pembagian3_id->EditCustomAttributes = "";
			$this->pembagian3_id->EditValue = ew_HtmlEncode($this->pembagian3_id->CurrentValue);
			$this->pembagian3_id->PlaceHolder = ew_RemoveHtml($this->pembagian3_id->FldCaption());

			// tgl_mulai_kerja
			$this->tgl_mulai_kerja->EditAttrs["class"] = "form-control";
			$this->tgl_mulai_kerja->EditCustomAttributes = "";
			$this->tgl_mulai_kerja->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_mulai_kerja->CurrentValue, 8));
			$this->tgl_mulai_kerja->PlaceHolder = ew_RemoveHtml($this->tgl_mulai_kerja->FldCaption());

			// tgl_resign
			$this->tgl_resign->EditAttrs["class"] = "form-control";
			$this->tgl_resign->EditCustomAttributes = "";
			$this->tgl_resign->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_resign->CurrentValue, 8));
			$this->tgl_resign->PlaceHolder = ew_RemoveHtml($this->tgl_resign->FldCaption());

			// gender
			$this->gender->EditAttrs["class"] = "form-control";
			$this->gender->EditCustomAttributes = "";
			$this->gender->EditValue = ew_HtmlEncode($this->gender->CurrentValue);
			$this->gender->PlaceHolder = ew_RemoveHtml($this->gender->FldCaption());

			// tgl_masuk_pertama
			$this->tgl_masuk_pertama->EditAttrs["class"] = "form-control";
			$this->tgl_masuk_pertama->EditCustomAttributes = "";
			$this->tgl_masuk_pertama->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_masuk_pertama->CurrentValue, 8));
			$this->tgl_masuk_pertama->PlaceHolder = ew_RemoveHtml($this->tgl_masuk_pertama->FldCaption());

			// photo_path
			$this->photo_path->EditAttrs["class"] = "form-control";
			$this->photo_path->EditCustomAttributes = "";
			$this->photo_path->EditValue = ew_HtmlEncode($this->photo_path->CurrentValue);
			$this->photo_path->PlaceHolder = ew_RemoveHtml($this->photo_path->FldCaption());

			// tmp_img
			$this->tmp_img->EditAttrs["class"] = "form-control";
			$this->tmp_img->EditCustomAttributes = "";
			$this->tmp_img->EditValue = ew_HtmlEncode($this->tmp_img->CurrentValue);
			$this->tmp_img->PlaceHolder = ew_RemoveHtml($this->tmp_img->FldCaption());

			// nama_bank
			$this->nama_bank->EditAttrs["class"] = "form-control";
			$this->nama_bank->EditCustomAttributes = "";
			$this->nama_bank->EditValue = ew_HtmlEncode($this->nama_bank->CurrentValue);
			$this->nama_bank->PlaceHolder = ew_RemoveHtml($this->nama_bank->FldCaption());

			// nama_rek
			$this->nama_rek->EditAttrs["class"] = "form-control";
			$this->nama_rek->EditCustomAttributes = "";
			$this->nama_rek->EditValue = ew_HtmlEncode($this->nama_rek->CurrentValue);
			$this->nama_rek->PlaceHolder = ew_RemoveHtml($this->nama_rek->FldCaption());

			// no_rek
			$this->no_rek->EditAttrs["class"] = "form-control";
			$this->no_rek->EditCustomAttributes = "";
			$this->no_rek->EditValue = ew_HtmlEncode($this->no_rek->CurrentValue);
			$this->no_rek->PlaceHolder = ew_RemoveHtml($this->no_rek->FldCaption());

			// Edit refer script
			// pegawai_id

			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";

			// pegawai_pin
			$this->pegawai_pin->LinkCustomAttributes = "";
			$this->pegawai_pin->HrefValue = "";

			// pegawai_nip
			$this->pegawai_nip->LinkCustomAttributes = "";
			$this->pegawai_nip->HrefValue = "";

			// pegawai_nama
			$this->pegawai_nama->LinkCustomAttributes = "";
			$this->pegawai_nama->HrefValue = "";

			// pegawai_pwd
			$this->pegawai_pwd->LinkCustomAttributes = "";
			$this->pegawai_pwd->HrefValue = "";

			// pegawai_rfid
			$this->pegawai_rfid->LinkCustomAttributes = "";
			$this->pegawai_rfid->HrefValue = "";

			// pegawai_privilege
			$this->pegawai_privilege->LinkCustomAttributes = "";
			$this->pegawai_privilege->HrefValue = "";

			// pegawai_telp
			$this->pegawai_telp->LinkCustomAttributes = "";
			$this->pegawai_telp->HrefValue = "";

			// pegawai_status
			$this->pegawai_status->LinkCustomAttributes = "";
			$this->pegawai_status->HrefValue = "";

			// tempat_lahir
			$this->tempat_lahir->LinkCustomAttributes = "";
			$this->tempat_lahir->HrefValue = "";

			// tgl_lahir
			$this->tgl_lahir->LinkCustomAttributes = "";
			$this->tgl_lahir->HrefValue = "";

			// pembagian1_id
			$this->pembagian1_id->LinkCustomAttributes = "";
			$this->pembagian1_id->HrefValue = "";

			// pembagian2_id
			$this->pembagian2_id->LinkCustomAttributes = "";
			$this->pembagian2_id->HrefValue = "";

			// pembagian3_id
			$this->pembagian3_id->LinkCustomAttributes = "";
			$this->pembagian3_id->HrefValue = "";

			// tgl_mulai_kerja
			$this->tgl_mulai_kerja->LinkCustomAttributes = "";
			$this->tgl_mulai_kerja->HrefValue = "";

			// tgl_resign
			$this->tgl_resign->LinkCustomAttributes = "";
			$this->tgl_resign->HrefValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";

			// tgl_masuk_pertama
			$this->tgl_masuk_pertama->LinkCustomAttributes = "";
			$this->tgl_masuk_pertama->HrefValue = "";

			// photo_path
			$this->photo_path->LinkCustomAttributes = "";
			$this->photo_path->HrefValue = "";

			// tmp_img
			$this->tmp_img->LinkCustomAttributes = "";
			$this->tmp_img->HrefValue = "";

			// nama_bank
			$this->nama_bank->LinkCustomAttributes = "";
			$this->nama_bank->HrefValue = "";

			// nama_rek
			$this->nama_rek->LinkCustomAttributes = "";
			$this->nama_rek->HrefValue = "";

			// no_rek
			$this->no_rek->LinkCustomAttributes = "";
			$this->no_rek->HrefValue = "";
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
		if (!$this->pegawai_id->FldIsDetailKey && !is_null($this->pegawai_id->FormValue) && $this->pegawai_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_id->FldCaption(), $this->pegawai_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pegawai_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pegawai_id->FldErrMsg());
		}
		if (!$this->pegawai_pin->FldIsDetailKey && !is_null($this->pegawai_pin->FormValue) && $this->pegawai_pin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_pin->FldCaption(), $this->pegawai_pin->ReqErrMsg));
		}
		if (!$this->pegawai_nama->FldIsDetailKey && !is_null($this->pegawai_nama->FormValue) && $this->pegawai_nama->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_nama->FldCaption(), $this->pegawai_nama->ReqErrMsg));
		}
		if (!$this->pegawai_pwd->FldIsDetailKey && !is_null($this->pegawai_pwd->FormValue) && $this->pegawai_pwd->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_pwd->FldCaption(), $this->pegawai_pwd->ReqErrMsg));
		}
		if (!$this->pegawai_rfid->FldIsDetailKey && !is_null($this->pegawai_rfid->FormValue) && $this->pegawai_rfid->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_rfid->FldCaption(), $this->pegawai_rfid->ReqErrMsg));
		}
		if (!$this->pegawai_privilege->FldIsDetailKey && !is_null($this->pegawai_privilege->FormValue) && $this->pegawai_privilege->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_privilege->FldCaption(), $this->pegawai_privilege->ReqErrMsg));
		}
		if (!$this->pegawai_status->FldIsDetailKey && !is_null($this->pegawai_status->FormValue) && $this->pegawai_status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_status->FldCaption(), $this->pegawai_status->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pegawai_status->FormValue)) {
			ew_AddMessage($gsFormError, $this->pegawai_status->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->tgl_lahir->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_lahir->FldErrMsg());
		}
		if (!ew_CheckInteger($this->pembagian1_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pembagian1_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->pembagian2_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pembagian2_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->pembagian3_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pembagian3_id->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->tgl_mulai_kerja->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_mulai_kerja->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->tgl_resign->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_resign->FldErrMsg());
		}
		if (!$this->gender->FldIsDetailKey && !is_null($this->gender->FormValue) && $this->gender->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->gender->FldCaption(), $this->gender->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->gender->FormValue)) {
			ew_AddMessage($gsFormError, $this->gender->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->tgl_masuk_pertama->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_masuk_pertama->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		if ($this->pegawai_pin->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`pegawai_pin` = '" . ew_AdjustSql($this->pegawai_pin->CurrentValue, $this->DBID) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->pegawai_pin->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->pegawai_pin->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// pegawai_id
			// pegawai_pin

			$this->pegawai_pin->SetDbValueDef($rsnew, $this->pegawai_pin->CurrentValue, "", $this->pegawai_pin->ReadOnly);

			// pegawai_nip
			$this->pegawai_nip->SetDbValueDef($rsnew, $this->pegawai_nip->CurrentValue, NULL, $this->pegawai_nip->ReadOnly);

			// pegawai_nama
			$this->pegawai_nama->SetDbValueDef($rsnew, $this->pegawai_nama->CurrentValue, "", $this->pegawai_nama->ReadOnly);

			// pegawai_pwd
			$this->pegawai_pwd->SetDbValueDef($rsnew, $this->pegawai_pwd->CurrentValue, "", $this->pegawai_pwd->ReadOnly);

			// pegawai_rfid
			$this->pegawai_rfid->SetDbValueDef($rsnew, $this->pegawai_rfid->CurrentValue, "", $this->pegawai_rfid->ReadOnly);

			// pegawai_privilege
			$this->pegawai_privilege->SetDbValueDef($rsnew, $this->pegawai_privilege->CurrentValue, "", $this->pegawai_privilege->ReadOnly);

			// pegawai_telp
			$this->pegawai_telp->SetDbValueDef($rsnew, $this->pegawai_telp->CurrentValue, NULL, $this->pegawai_telp->ReadOnly);

			// pegawai_status
			$this->pegawai_status->SetDbValueDef($rsnew, $this->pegawai_status->CurrentValue, 0, $this->pegawai_status->ReadOnly);

			// tempat_lahir
			$this->tempat_lahir->SetDbValueDef($rsnew, $this->tempat_lahir->CurrentValue, NULL, $this->tempat_lahir->ReadOnly);

			// tgl_lahir
			$this->tgl_lahir->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_lahir->CurrentValue, 0), NULL, $this->tgl_lahir->ReadOnly);

			// pembagian1_id
			$this->pembagian1_id->SetDbValueDef($rsnew, $this->pembagian1_id->CurrentValue, NULL, $this->pembagian1_id->ReadOnly);

			// pembagian2_id
			$this->pembagian2_id->SetDbValueDef($rsnew, $this->pembagian2_id->CurrentValue, NULL, $this->pembagian2_id->ReadOnly);

			// pembagian3_id
			$this->pembagian3_id->SetDbValueDef($rsnew, $this->pembagian3_id->CurrentValue, NULL, $this->pembagian3_id->ReadOnly);

			// tgl_mulai_kerja
			$this->tgl_mulai_kerja->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_mulai_kerja->CurrentValue, 0), NULL, $this->tgl_mulai_kerja->ReadOnly);

			// tgl_resign
			$this->tgl_resign->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_resign->CurrentValue, 0), NULL, $this->tgl_resign->ReadOnly);

			// gender
			$this->gender->SetDbValueDef($rsnew, $this->gender->CurrentValue, 0, $this->gender->ReadOnly);

			// tgl_masuk_pertama
			$this->tgl_masuk_pertama->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_masuk_pertama->CurrentValue, 0), NULL, $this->tgl_masuk_pertama->ReadOnly);

			// photo_path
			$this->photo_path->SetDbValueDef($rsnew, $this->photo_path->CurrentValue, NULL, $this->photo_path->ReadOnly);

			// tmp_img
			$this->tmp_img->SetDbValueDef($rsnew, $this->tmp_img->CurrentValue, NULL, $this->tmp_img->ReadOnly);

			// nama_bank
			$this->nama_bank->SetDbValueDef($rsnew, $this->nama_bank->CurrentValue, NULL, $this->nama_bank->ReadOnly);

			// nama_rek
			$this->nama_rek->SetDbValueDef($rsnew, $this->nama_rek->CurrentValue, NULL, $this->nama_rek->ReadOnly);

			// no_rek
			$this->no_rek->SetDbValueDef($rsnew, $this->no_rek->CurrentValue, NULL, $this->no_rek->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pegawailist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($pegawai_edit)) $pegawai_edit = new cpegawai_edit();

// Page init
$pegawai_edit->Page_Init();

// Page main
$pegawai_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pegawai_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fpegawaiedit = new ew_Form("fpegawaiedit", "edit");

// Validate form
fpegawaiedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai->pegawai_id->FldCaption(), $pegawai->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_pin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai->pegawai_pin->FldCaption(), $pegawai->pegawai_pin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_nama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai->pegawai_nama->FldCaption(), $pegawai->pegawai_nama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_pwd");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai->pegawai_pwd->FldCaption(), $pegawai->pegawai_pwd->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_rfid");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai->pegawai_rfid->FldCaption(), $pegawai->pegawai_rfid->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_privilege");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai->pegawai_privilege->FldCaption(), $pegawai->pegawai_privilege->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai->pegawai_status->FldCaption(), $pegawai->pegawai_status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_status");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai->pegawai_status->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_lahir");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai->tgl_lahir->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pembagian1_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai->pembagian1_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pembagian2_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai->pembagian2_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pembagian3_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai->pembagian3_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_mulai_kerja");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai->tgl_mulai_kerja->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_resign");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai->tgl_resign->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_gender");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai->gender->FldCaption(), $pegawai->gender->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_gender");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai->gender->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_masuk_pertama");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai->tgl_masuk_pertama->FldErrMsg()) ?>");

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
fpegawaiedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpegawaiedit.ValidateRequired = true;
<?php } else { ?>
fpegawaiedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$pegawai_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $pegawai_edit->ShowPageHeader(); ?>
<?php
$pegawai_edit->ShowMessage();
?>
<form name="fpegawaiedit" id="fpegawaiedit" class="<?php echo $pegawai_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pegawai_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pegawai_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pegawai">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($pegawai_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($pegawai->pegawai_id->Visible) { // pegawai_id ?>
	<div id="r_pegawai_id" class="form-group">
		<label id="elh_pegawai_pegawai_id" for="x_pegawai_id" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pegawai_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pegawai_id->CellAttributes() ?>>
<span id="el_pegawai_pegawai_id">
<span<?php echo $pegawai->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai->pegawai_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai" data-field="x_pegawai_id" name="x_pegawai_id" id="x_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai->pegawai_id->CurrentValue) ?>">
<?php echo $pegawai->pegawai_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pegawai_pin->Visible) { // pegawai_pin ?>
	<div id="r_pegawai_pin" class="form-group">
		<label id="elh_pegawai_pegawai_pin" for="x_pegawai_pin" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pegawai_pin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pegawai_pin->CellAttributes() ?>>
<span id="el_pegawai_pegawai_pin">
<input type="text" data-table="pegawai" data-field="x_pegawai_pin" name="x_pegawai_pin" id="x_pegawai_pin" size="30" maxlength="32" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_pin->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_pin->EditValue ?>"<?php echo $pegawai->pegawai_pin->EditAttributes() ?>>
</span>
<?php echo $pegawai->pegawai_pin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pegawai_nip->Visible) { // pegawai_nip ?>
	<div id="r_pegawai_nip" class="form-group">
		<label id="elh_pegawai_pegawai_nip" for="x_pegawai_nip" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pegawai_nip->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pegawai_nip->CellAttributes() ?>>
<span id="el_pegawai_pegawai_nip">
<input type="text" data-table="pegawai" data-field="x_pegawai_nip" name="x_pegawai_nip" id="x_pegawai_nip" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_nip->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_nip->EditValue ?>"<?php echo $pegawai->pegawai_nip->EditAttributes() ?>>
</span>
<?php echo $pegawai->pegawai_nip->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pegawai_nama->Visible) { // pegawai_nama ?>
	<div id="r_pegawai_nama" class="form-group">
		<label id="elh_pegawai_pegawai_nama" for="x_pegawai_nama" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pegawai_nama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pegawai_nama->CellAttributes() ?>>
<span id="el_pegawai_pegawai_nama">
<input type="text" data-table="pegawai" data-field="x_pegawai_nama" name="x_pegawai_nama" id="x_pegawai_nama" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_nama->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_nama->EditValue ?>"<?php echo $pegawai->pegawai_nama->EditAttributes() ?>>
</span>
<?php echo $pegawai->pegawai_nama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pegawai_pwd->Visible) { // pegawai_pwd ?>
	<div id="r_pegawai_pwd" class="form-group">
		<label id="elh_pegawai_pegawai_pwd" for="x_pegawai_pwd" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pegawai_pwd->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pegawai_pwd->CellAttributes() ?>>
<span id="el_pegawai_pegawai_pwd">
<input type="text" data-table="pegawai" data-field="x_pegawai_pwd" name="x_pegawai_pwd" id="x_pegawai_pwd" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_pwd->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_pwd->EditValue ?>"<?php echo $pegawai->pegawai_pwd->EditAttributes() ?>>
</span>
<?php echo $pegawai->pegawai_pwd->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pegawai_rfid->Visible) { // pegawai_rfid ?>
	<div id="r_pegawai_rfid" class="form-group">
		<label id="elh_pegawai_pegawai_rfid" for="x_pegawai_rfid" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pegawai_rfid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pegawai_rfid->CellAttributes() ?>>
<span id="el_pegawai_pegawai_rfid">
<input type="text" data-table="pegawai" data-field="x_pegawai_rfid" name="x_pegawai_rfid" id="x_pegawai_rfid" size="30" maxlength="32" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_rfid->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_rfid->EditValue ?>"<?php echo $pegawai->pegawai_rfid->EditAttributes() ?>>
</span>
<?php echo $pegawai->pegawai_rfid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pegawai_privilege->Visible) { // pegawai_privilege ?>
	<div id="r_pegawai_privilege" class="form-group">
		<label id="elh_pegawai_pegawai_privilege" for="x_pegawai_privilege" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pegawai_privilege->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pegawai_privilege->CellAttributes() ?>>
<span id="el_pegawai_pegawai_privilege">
<input type="text" data-table="pegawai" data-field="x_pegawai_privilege" name="x_pegawai_privilege" id="x_pegawai_privilege" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_privilege->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_privilege->EditValue ?>"<?php echo $pegawai->pegawai_privilege->EditAttributes() ?>>
</span>
<?php echo $pegawai->pegawai_privilege->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pegawai_telp->Visible) { // pegawai_telp ?>
	<div id="r_pegawai_telp" class="form-group">
		<label id="elh_pegawai_pegawai_telp" for="x_pegawai_telp" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pegawai_telp->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pegawai_telp->CellAttributes() ?>>
<span id="el_pegawai_pegawai_telp">
<input type="text" data-table="pegawai" data-field="x_pegawai_telp" name="x_pegawai_telp" id="x_pegawai_telp" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_telp->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_telp->EditValue ?>"<?php echo $pegawai->pegawai_telp->EditAttributes() ?>>
</span>
<?php echo $pegawai->pegawai_telp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pegawai_status->Visible) { // pegawai_status ?>
	<div id="r_pegawai_status" class="form-group">
		<label id="elh_pegawai_pegawai_status" for="x_pegawai_status" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pegawai_status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pegawai_status->CellAttributes() ?>>
<span id="el_pegawai_pegawai_status">
<input type="text" data-table="pegawai" data-field="x_pegawai_status" name="x_pegawai_status" id="x_pegawai_status" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai->pegawai_status->getPlaceHolder()) ?>" value="<?php echo $pegawai->pegawai_status->EditValue ?>"<?php echo $pegawai->pegawai_status->EditAttributes() ?>>
</span>
<?php echo $pegawai->pegawai_status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->tempat_lahir->Visible) { // tempat_lahir ?>
	<div id="r_tempat_lahir" class="form-group">
		<label id="elh_pegawai_tempat_lahir" for="x_tempat_lahir" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->tempat_lahir->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->tempat_lahir->CellAttributes() ?>>
<span id="el_pegawai_tempat_lahir">
<input type="text" data-table="pegawai" data-field="x_tempat_lahir" name="x_tempat_lahir" id="x_tempat_lahir" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pegawai->tempat_lahir->getPlaceHolder()) ?>" value="<?php echo $pegawai->tempat_lahir->EditValue ?>"<?php echo $pegawai->tempat_lahir->EditAttributes() ?>>
</span>
<?php echo $pegawai->tempat_lahir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->tgl_lahir->Visible) { // tgl_lahir ?>
	<div id="r_tgl_lahir" class="form-group">
		<label id="elh_pegawai_tgl_lahir" for="x_tgl_lahir" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->tgl_lahir->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->tgl_lahir->CellAttributes() ?>>
<span id="el_pegawai_tgl_lahir">
<input type="text" data-table="pegawai" data-field="x_tgl_lahir" name="x_tgl_lahir" id="x_tgl_lahir" placeholder="<?php echo ew_HtmlEncode($pegawai->tgl_lahir->getPlaceHolder()) ?>" value="<?php echo $pegawai->tgl_lahir->EditValue ?>"<?php echo $pegawai->tgl_lahir->EditAttributes() ?>>
</span>
<?php echo $pegawai->tgl_lahir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pembagian1_id->Visible) { // pembagian1_id ?>
	<div id="r_pembagian1_id" class="form-group">
		<label id="elh_pegawai_pembagian1_id" for="x_pembagian1_id" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pembagian1_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pembagian1_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian1_id">
<input type="text" data-table="pegawai" data-field="x_pembagian1_id" name="x_pembagian1_id" id="x_pembagian1_id" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai->pembagian1_id->getPlaceHolder()) ?>" value="<?php echo $pegawai->pembagian1_id->EditValue ?>"<?php echo $pegawai->pembagian1_id->EditAttributes() ?>>
</span>
<?php echo $pegawai->pembagian1_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pembagian2_id->Visible) { // pembagian2_id ?>
	<div id="r_pembagian2_id" class="form-group">
		<label id="elh_pegawai_pembagian2_id" for="x_pembagian2_id" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pembagian2_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pembagian2_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian2_id">
<input type="text" data-table="pegawai" data-field="x_pembagian2_id" name="x_pembagian2_id" id="x_pembagian2_id" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai->pembagian2_id->getPlaceHolder()) ?>" value="<?php echo $pegawai->pembagian2_id->EditValue ?>"<?php echo $pegawai->pembagian2_id->EditAttributes() ?>>
</span>
<?php echo $pegawai->pembagian2_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->pembagian3_id->Visible) { // pembagian3_id ?>
	<div id="r_pembagian3_id" class="form-group">
		<label id="elh_pegawai_pembagian3_id" for="x_pembagian3_id" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->pembagian3_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->pembagian3_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian3_id">
<input type="text" data-table="pegawai" data-field="x_pembagian3_id" name="x_pembagian3_id" id="x_pembagian3_id" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai->pembagian3_id->getPlaceHolder()) ?>" value="<?php echo $pegawai->pembagian3_id->EditValue ?>"<?php echo $pegawai->pembagian3_id->EditAttributes() ?>>
</span>
<?php echo $pegawai->pembagian3_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->tgl_mulai_kerja->Visible) { // tgl_mulai_kerja ?>
	<div id="r_tgl_mulai_kerja" class="form-group">
		<label id="elh_pegawai_tgl_mulai_kerja" for="x_tgl_mulai_kerja" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->tgl_mulai_kerja->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->tgl_mulai_kerja->CellAttributes() ?>>
<span id="el_pegawai_tgl_mulai_kerja">
<input type="text" data-table="pegawai" data-field="x_tgl_mulai_kerja" name="x_tgl_mulai_kerja" id="x_tgl_mulai_kerja" placeholder="<?php echo ew_HtmlEncode($pegawai->tgl_mulai_kerja->getPlaceHolder()) ?>" value="<?php echo $pegawai->tgl_mulai_kerja->EditValue ?>"<?php echo $pegawai->tgl_mulai_kerja->EditAttributes() ?>>
</span>
<?php echo $pegawai->tgl_mulai_kerja->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->tgl_resign->Visible) { // tgl_resign ?>
	<div id="r_tgl_resign" class="form-group">
		<label id="elh_pegawai_tgl_resign" for="x_tgl_resign" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->tgl_resign->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->tgl_resign->CellAttributes() ?>>
<span id="el_pegawai_tgl_resign">
<input type="text" data-table="pegawai" data-field="x_tgl_resign" name="x_tgl_resign" id="x_tgl_resign" placeholder="<?php echo ew_HtmlEncode($pegawai->tgl_resign->getPlaceHolder()) ?>" value="<?php echo $pegawai->tgl_resign->EditValue ?>"<?php echo $pegawai->tgl_resign->EditAttributes() ?>>
</span>
<?php echo $pegawai->tgl_resign->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->gender->Visible) { // gender ?>
	<div id="r_gender" class="form-group">
		<label id="elh_pegawai_gender" for="x_gender" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->gender->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->gender->CellAttributes() ?>>
<span id="el_pegawai_gender">
<input type="text" data-table="pegawai" data-field="x_gender" name="x_gender" id="x_gender" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai->gender->getPlaceHolder()) ?>" value="<?php echo $pegawai->gender->EditValue ?>"<?php echo $pegawai->gender->EditAttributes() ?>>
</span>
<?php echo $pegawai->gender->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->tgl_masuk_pertama->Visible) { // tgl_masuk_pertama ?>
	<div id="r_tgl_masuk_pertama" class="form-group">
		<label id="elh_pegawai_tgl_masuk_pertama" for="x_tgl_masuk_pertama" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->tgl_masuk_pertama->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->tgl_masuk_pertama->CellAttributes() ?>>
<span id="el_pegawai_tgl_masuk_pertama">
<input type="text" data-table="pegawai" data-field="x_tgl_masuk_pertama" name="x_tgl_masuk_pertama" id="x_tgl_masuk_pertama" placeholder="<?php echo ew_HtmlEncode($pegawai->tgl_masuk_pertama->getPlaceHolder()) ?>" value="<?php echo $pegawai->tgl_masuk_pertama->EditValue ?>"<?php echo $pegawai->tgl_masuk_pertama->EditAttributes() ?>>
</span>
<?php echo $pegawai->tgl_masuk_pertama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->photo_path->Visible) { // photo_path ?>
	<div id="r_photo_path" class="form-group">
		<label id="elh_pegawai_photo_path" for="x_photo_path" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->photo_path->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->photo_path->CellAttributes() ?>>
<span id="el_pegawai_photo_path">
<input type="text" data-table="pegawai" data-field="x_photo_path" name="x_photo_path" id="x_photo_path" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pegawai->photo_path->getPlaceHolder()) ?>" value="<?php echo $pegawai->photo_path->EditValue ?>"<?php echo $pegawai->photo_path->EditAttributes() ?>>
</span>
<?php echo $pegawai->photo_path->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->tmp_img->Visible) { // tmp_img ?>
	<div id="r_tmp_img" class="form-group">
		<label id="elh_pegawai_tmp_img" for="x_tmp_img" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->tmp_img->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->tmp_img->CellAttributes() ?>>
<span id="el_pegawai_tmp_img">
<textarea data-table="pegawai" data-field="x_tmp_img" name="x_tmp_img" id="x_tmp_img" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($pegawai->tmp_img->getPlaceHolder()) ?>"<?php echo $pegawai->tmp_img->EditAttributes() ?>><?php echo $pegawai->tmp_img->EditValue ?></textarea>
</span>
<?php echo $pegawai->tmp_img->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->nama_bank->Visible) { // nama_bank ?>
	<div id="r_nama_bank" class="form-group">
		<label id="elh_pegawai_nama_bank" for="x_nama_bank" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->nama_bank->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->nama_bank->CellAttributes() ?>>
<span id="el_pegawai_nama_bank">
<input type="text" data-table="pegawai" data-field="x_nama_bank" name="x_nama_bank" id="x_nama_bank" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pegawai->nama_bank->getPlaceHolder()) ?>" value="<?php echo $pegawai->nama_bank->EditValue ?>"<?php echo $pegawai->nama_bank->EditAttributes() ?>>
</span>
<?php echo $pegawai->nama_bank->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->nama_rek->Visible) { // nama_rek ?>
	<div id="r_nama_rek" class="form-group">
		<label id="elh_pegawai_nama_rek" for="x_nama_rek" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->nama_rek->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->nama_rek->CellAttributes() ?>>
<span id="el_pegawai_nama_rek">
<input type="text" data-table="pegawai" data-field="x_nama_rek" name="x_nama_rek" id="x_nama_rek" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pegawai->nama_rek->getPlaceHolder()) ?>" value="<?php echo $pegawai->nama_rek->EditValue ?>"<?php echo $pegawai->nama_rek->EditAttributes() ?>>
</span>
<?php echo $pegawai->nama_rek->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai->no_rek->Visible) { // no_rek ?>
	<div id="r_no_rek" class="form-group">
		<label id="elh_pegawai_no_rek" for="x_no_rek" class="col-sm-2 control-label ewLabel"><?php echo $pegawai->no_rek->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai->no_rek->CellAttributes() ?>>
<span id="el_pegawai_no_rek">
<input type="text" data-table="pegawai" data-field="x_no_rek" name="x_no_rek" id="x_no_rek" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($pegawai->no_rek->getPlaceHolder()) ?>" value="<?php echo $pegawai->no_rek->EditValue ?>"<?php echo $pegawai->no_rek->EditAttributes() ?>>
</span>
<?php echo $pegawai->no_rek->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$pegawai_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pegawai_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fpegawaiedit.Init();
</script>
<?php
$pegawai_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pegawai_edit->Page_Terminate();
?>
