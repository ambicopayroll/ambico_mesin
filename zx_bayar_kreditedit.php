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

$zx_bayar_kredit_edit = NULL; // Initialize page object first

class czx_bayar_kredit_edit extends czx_bayar_kredit {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'zx_bayar_kredit';

	// Page object name
	var $PageObjName = 'zx_bayar_kredit_edit';

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

		// Table object (zx_bayar_kredit)
		if (!isset($GLOBALS["zx_bayar_kredit"]) || get_class($GLOBALS["zx_bayar_kredit"]) == "czx_bayar_kredit") {
			$GLOBALS["zx_bayar_kredit"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["zx_bayar_kredit"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("zx_bayar_kreditlist.php"));
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
		$this->id_bayar->SetVisibility();
		$this->tgl_bayar->SetVisibility();
		$this->id_kredit->SetVisibility();
		$this->no_urut->SetVisibility();
		$this->tgl_jt->SetVisibility();
		$this->debet->SetVisibility();
		$this->angs_pokok->SetVisibility();
		$this->bunga->SetVisibility();
		$this->emp_id_auto->SetVisibility();
		$this->keterangan->SetVisibility();
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
		if (@$_GET["id_bayar"] <> "") {
			$this->id_bayar->setQueryStringValue($_GET["id_bayar"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id_bayar->CurrentValue == "") {
			$this->Page_Terminate("zx_bayar_kreditlist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("zx_bayar_kreditlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "zx_bayar_kreditlist.php")
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
		if (!$this->id_bayar->FldIsDetailKey) {
			$this->id_bayar->setFormValue($objForm->GetValue("x_id_bayar"));
		}
		if (!$this->tgl_bayar->FldIsDetailKey) {
			$this->tgl_bayar->setFormValue($objForm->GetValue("x_tgl_bayar"));
			$this->tgl_bayar->CurrentValue = ew_UnFormatDateTime($this->tgl_bayar->CurrentValue, 0);
		}
		if (!$this->id_kredit->FldIsDetailKey) {
			$this->id_kredit->setFormValue($objForm->GetValue("x_id_kredit"));
		}
		if (!$this->no_urut->FldIsDetailKey) {
			$this->no_urut->setFormValue($objForm->GetValue("x_no_urut"));
		}
		if (!$this->tgl_jt->FldIsDetailKey) {
			$this->tgl_jt->setFormValue($objForm->GetValue("x_tgl_jt"));
			$this->tgl_jt->CurrentValue = ew_UnFormatDateTime($this->tgl_jt->CurrentValue, 0);
		}
		if (!$this->debet->FldIsDetailKey) {
			$this->debet->setFormValue($objForm->GetValue("x_debet"));
		}
		if (!$this->angs_pokok->FldIsDetailKey) {
			$this->angs_pokok->setFormValue($objForm->GetValue("x_angs_pokok"));
		}
		if (!$this->bunga->FldIsDetailKey) {
			$this->bunga->setFormValue($objForm->GetValue("x_bunga"));
		}
		if (!$this->emp_id_auto->FldIsDetailKey) {
			$this->emp_id_auto->setFormValue($objForm->GetValue("x_emp_id_auto"));
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->lastupdate_date->FldIsDetailKey) {
			$this->lastupdate_date->setFormValue($objForm->GetValue("x_lastupdate_date"));
			$this->lastupdate_date->CurrentValue = ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0);
		}
		if (!$this->lastupdate_user->FldIsDetailKey) {
			$this->lastupdate_user->setFormValue($objForm->GetValue("x_lastupdate_user"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_bayar->CurrentValue = $this->id_bayar->FormValue;
		$this->tgl_bayar->CurrentValue = $this->tgl_bayar->FormValue;
		$this->tgl_bayar->CurrentValue = ew_UnFormatDateTime($this->tgl_bayar->CurrentValue, 0);
		$this->id_kredit->CurrentValue = $this->id_kredit->FormValue;
		$this->no_urut->CurrentValue = $this->no_urut->FormValue;
		$this->tgl_jt->CurrentValue = $this->tgl_jt->FormValue;
		$this->tgl_jt->CurrentValue = ew_UnFormatDateTime($this->tgl_jt->CurrentValue, 0);
		$this->debet->CurrentValue = $this->debet->FormValue;
		$this->angs_pokok->CurrentValue = $this->angs_pokok->FormValue;
		$this->bunga->CurrentValue = $this->bunga->FormValue;
		$this->emp_id_auto->CurrentValue = $this->emp_id_auto->FormValue;
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->lastupdate_date->CurrentValue = $this->lastupdate_date->FormValue;
		$this->lastupdate_date->CurrentValue = ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0);
		$this->lastupdate_user->CurrentValue = $this->lastupdate_user->FormValue;
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

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

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_bayar
			$this->id_bayar->EditAttrs["class"] = "form-control";
			$this->id_bayar->EditCustomAttributes = "";
			$this->id_bayar->EditValue = $this->id_bayar->CurrentValue;
			$this->id_bayar->ViewCustomAttributes = "";

			// tgl_bayar
			$this->tgl_bayar->EditAttrs["class"] = "form-control";
			$this->tgl_bayar->EditCustomAttributes = "";
			$this->tgl_bayar->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_bayar->CurrentValue, 8));
			$this->tgl_bayar->PlaceHolder = ew_RemoveHtml($this->tgl_bayar->FldCaption());

			// id_kredit
			$this->id_kredit->EditAttrs["class"] = "form-control";
			$this->id_kredit->EditCustomAttributes = "";
			$this->id_kredit->EditValue = ew_HtmlEncode($this->id_kredit->CurrentValue);
			$this->id_kredit->PlaceHolder = ew_RemoveHtml($this->id_kredit->FldCaption());

			// no_urut
			$this->no_urut->EditAttrs["class"] = "form-control";
			$this->no_urut->EditCustomAttributes = "";
			$this->no_urut->EditValue = ew_HtmlEncode($this->no_urut->CurrentValue);
			$this->no_urut->PlaceHolder = ew_RemoveHtml($this->no_urut->FldCaption());

			// tgl_jt
			$this->tgl_jt->EditAttrs["class"] = "form-control";
			$this->tgl_jt->EditCustomAttributes = "";
			$this->tgl_jt->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_jt->CurrentValue, 8));
			$this->tgl_jt->PlaceHolder = ew_RemoveHtml($this->tgl_jt->FldCaption());

			// debet
			$this->debet->EditAttrs["class"] = "form-control";
			$this->debet->EditCustomAttributes = "";
			$this->debet->EditValue = ew_HtmlEncode($this->debet->CurrentValue);
			$this->debet->PlaceHolder = ew_RemoveHtml($this->debet->FldCaption());
			if (strval($this->debet->EditValue) <> "" && is_numeric($this->debet->EditValue)) $this->debet->EditValue = ew_FormatNumber($this->debet->EditValue, -2, -1, -2, 0);

			// angs_pokok
			$this->angs_pokok->EditAttrs["class"] = "form-control";
			$this->angs_pokok->EditCustomAttributes = "";
			$this->angs_pokok->EditValue = ew_HtmlEncode($this->angs_pokok->CurrentValue);
			$this->angs_pokok->PlaceHolder = ew_RemoveHtml($this->angs_pokok->FldCaption());
			if (strval($this->angs_pokok->EditValue) <> "" && is_numeric($this->angs_pokok->EditValue)) $this->angs_pokok->EditValue = ew_FormatNumber($this->angs_pokok->EditValue, -2, -1, -2, 0);

			// bunga
			$this->bunga->EditAttrs["class"] = "form-control";
			$this->bunga->EditCustomAttributes = "";
			$this->bunga->EditValue = ew_HtmlEncode($this->bunga->CurrentValue);
			$this->bunga->PlaceHolder = ew_RemoveHtml($this->bunga->FldCaption());
			if (strval($this->bunga->EditValue) <> "" && is_numeric($this->bunga->EditValue)) $this->bunga->EditValue = ew_FormatNumber($this->bunga->EditValue, -2, -1, -2, 0);

			// emp_id_auto
			$this->emp_id_auto->EditAttrs["class"] = "form-control";
			$this->emp_id_auto->EditCustomAttributes = "";
			$this->emp_id_auto->EditValue = ew_HtmlEncode($this->emp_id_auto->CurrentValue);
			$this->emp_id_auto->PlaceHolder = ew_RemoveHtml($this->emp_id_auto->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// lastupdate_date
			$this->lastupdate_date->EditAttrs["class"] = "form-control";
			$this->lastupdate_date->EditCustomAttributes = "";
			$this->lastupdate_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->lastupdate_date->CurrentValue, 8));
			$this->lastupdate_date->PlaceHolder = ew_RemoveHtml($this->lastupdate_date->FldCaption());

			// lastupdate_user
			$this->lastupdate_user->EditAttrs["class"] = "form-control";
			$this->lastupdate_user->EditCustomAttributes = "";
			$this->lastupdate_user->EditValue = ew_HtmlEncode($this->lastupdate_user->CurrentValue);
			$this->lastupdate_user->PlaceHolder = ew_RemoveHtml($this->lastupdate_user->FldCaption());

			// Edit refer script
			// id_bayar

			$this->id_bayar->LinkCustomAttributes = "";
			$this->id_bayar->HrefValue = "";

			// tgl_bayar
			$this->tgl_bayar->LinkCustomAttributes = "";
			$this->tgl_bayar->HrefValue = "";

			// id_kredit
			$this->id_kredit->LinkCustomAttributes = "";
			$this->id_kredit->HrefValue = "";

			// no_urut
			$this->no_urut->LinkCustomAttributes = "";
			$this->no_urut->HrefValue = "";

			// tgl_jt
			$this->tgl_jt->LinkCustomAttributes = "";
			$this->tgl_jt->HrefValue = "";

			// debet
			$this->debet->LinkCustomAttributes = "";
			$this->debet->HrefValue = "";

			// angs_pokok
			$this->angs_pokok->LinkCustomAttributes = "";
			$this->angs_pokok->HrefValue = "";

			// bunga
			$this->bunga->LinkCustomAttributes = "";
			$this->bunga->HrefValue = "";

			// emp_id_auto
			$this->emp_id_auto->LinkCustomAttributes = "";
			$this->emp_id_auto->HrefValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
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
		if (!$this->id_bayar->FldIsDetailKey && !is_null($this->id_bayar->FormValue) && $this->id_bayar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_bayar->FldCaption(), $this->id_bayar->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id_bayar->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_bayar->FldErrMsg());
		}
		if (!$this->tgl_bayar->FldIsDetailKey && !is_null($this->tgl_bayar->FormValue) && $this->tgl_bayar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl_bayar->FldCaption(), $this->tgl_bayar->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl_bayar->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_bayar->FldErrMsg());
		}
		if (!$this->id_kredit->FldIsDetailKey && !is_null($this->id_kredit->FormValue) && $this->id_kredit->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_kredit->FldCaption(), $this->id_kredit->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id_kredit->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_kredit->FldErrMsg());
		}
		if (!$this->no_urut->FldIsDetailKey && !is_null($this->no_urut->FormValue) && $this->no_urut->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->no_urut->FldCaption(), $this->no_urut->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->no_urut->FormValue)) {
			ew_AddMessage($gsFormError, $this->no_urut->FldErrMsg());
		}
		if (!$this->tgl_jt->FldIsDetailKey && !is_null($this->tgl_jt->FormValue) && $this->tgl_jt->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl_jt->FldCaption(), $this->tgl_jt->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl_jt->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_jt->FldErrMsg());
		}
		if (!$this->debet->FldIsDetailKey && !is_null($this->debet->FormValue) && $this->debet->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet->FldCaption(), $this->debet->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet->FldErrMsg());
		}
		if (!$this->angs_pokok->FldIsDetailKey && !is_null($this->angs_pokok->FormValue) && $this->angs_pokok->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->angs_pokok->FldCaption(), $this->angs_pokok->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->angs_pokok->FormValue)) {
			ew_AddMessage($gsFormError, $this->angs_pokok->FldErrMsg());
		}
		if (!$this->bunga->FldIsDetailKey && !is_null($this->bunga->FormValue) && $this->bunga->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bunga->FldCaption(), $this->bunga->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->bunga->FormValue)) {
			ew_AddMessage($gsFormError, $this->bunga->FldErrMsg());
		}
		if (!$this->emp_id_auto->FldIsDetailKey && !is_null($this->emp_id_auto->FormValue) && $this->emp_id_auto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->emp_id_auto->FldCaption(), $this->emp_id_auto->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->emp_id_auto->FormValue)) {
			ew_AddMessage($gsFormError, $this->emp_id_auto->FldErrMsg());
		}
		if (!$this->keterangan->FldIsDetailKey && !is_null($this->keterangan->FormValue) && $this->keterangan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->keterangan->FldCaption(), $this->keterangan->ReqErrMsg));
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->status->FormValue)) {
			ew_AddMessage($gsFormError, $this->status->FldErrMsg());
		}
		if (!$this->lastupdate_date->FldIsDetailKey && !is_null($this->lastupdate_date->FormValue) && $this->lastupdate_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastupdate_date->FldCaption(), $this->lastupdate_date->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->lastupdate_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->lastupdate_date->FldErrMsg());
		}
		if (!$this->lastupdate_user->FldIsDetailKey && !is_null($this->lastupdate_user->FormValue) && $this->lastupdate_user->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastupdate_user->FldCaption(), $this->lastupdate_user->ReqErrMsg));
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

			// id_bayar
			// tgl_bayar

			$this->tgl_bayar->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_bayar->CurrentValue, 0), ew_CurrentDate(), $this->tgl_bayar->ReadOnly);

			// id_kredit
			$this->id_kredit->SetDbValueDef($rsnew, $this->id_kredit->CurrentValue, 0, $this->id_kredit->ReadOnly);

			// no_urut
			$this->no_urut->SetDbValueDef($rsnew, $this->no_urut->CurrentValue, 0, $this->no_urut->ReadOnly);

			// tgl_jt
			$this->tgl_jt->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_jt->CurrentValue, 0), ew_CurrentDate(), $this->tgl_jt->ReadOnly);

			// debet
			$this->debet->SetDbValueDef($rsnew, $this->debet->CurrentValue, 0, $this->debet->ReadOnly);

			// angs_pokok
			$this->angs_pokok->SetDbValueDef($rsnew, $this->angs_pokok->CurrentValue, 0, $this->angs_pokok->ReadOnly);

			// bunga
			$this->bunga->SetDbValueDef($rsnew, $this->bunga->CurrentValue, 0, $this->bunga->ReadOnly);

			// emp_id_auto
			$this->emp_id_auto->SetDbValueDef($rsnew, $this->emp_id_auto->CurrentValue, 0, $this->emp_id_auto->ReadOnly);

			// keterangan
			$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, "", $this->keterangan->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, 0, $this->status->ReadOnly);

			// lastupdate_date
			$this->lastupdate_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0), ew_CurrentDate(), $this->lastupdate_date->ReadOnly);

			// lastupdate_user
			$this->lastupdate_user->SetDbValueDef($rsnew, $this->lastupdate_user->CurrentValue, "", $this->lastupdate_user->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("zx_bayar_kreditlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($zx_bayar_kredit_edit)) $zx_bayar_kredit_edit = new czx_bayar_kredit_edit();

// Page init
$zx_bayar_kredit_edit->Page_Init();

// Page main
$zx_bayar_kredit_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$zx_bayar_kredit_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fzx_bayar_kreditedit = new ew_Form("fzx_bayar_kreditedit", "edit");

// Validate form
fzx_bayar_kreditedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_bayar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->id_bayar->FldCaption(), $zx_bayar_kredit->id_bayar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_bayar");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_bayar_kredit->id_bayar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_bayar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->tgl_bayar->FldCaption(), $zx_bayar_kredit->tgl_bayar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_bayar");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_bayar_kredit->tgl_bayar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_kredit");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->id_kredit->FldCaption(), $zx_bayar_kredit->id_kredit->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_kredit");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_bayar_kredit->id_kredit->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_no_urut");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->no_urut->FldCaption(), $zx_bayar_kredit->no_urut->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_no_urut");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_bayar_kredit->no_urut->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_jt");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->tgl_jt->FldCaption(), $zx_bayar_kredit->tgl_jt->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_jt");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_bayar_kredit->tgl_jt->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->debet->FldCaption(), $zx_bayar_kredit->debet->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_bayar_kredit->debet->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angs_pokok");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->angs_pokok->FldCaption(), $zx_bayar_kredit->angs_pokok->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angs_pokok");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_bayar_kredit->angs_pokok->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->bunga->FldCaption(), $zx_bayar_kredit->bunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bunga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_bayar_kredit->bunga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_emp_id_auto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->emp_id_auto->FldCaption(), $zx_bayar_kredit->emp_id_auto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_emp_id_auto");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_bayar_kredit->emp_id_auto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->keterangan->FldCaption(), $zx_bayar_kredit->keterangan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->status->FldCaption(), $zx_bayar_kredit->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_bayar_kredit->status->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->lastupdate_date->FldCaption(), $zx_bayar_kredit->lastupdate_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_bayar_kredit->lastupdate_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_bayar_kredit->lastupdate_user->FldCaption(), $zx_bayar_kredit->lastupdate_user->ReqErrMsg)) ?>");

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
fzx_bayar_kreditedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fzx_bayar_kreditedit.ValidateRequired = true;
<?php } else { ?>
fzx_bayar_kreditedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$zx_bayar_kredit_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $zx_bayar_kredit_edit->ShowPageHeader(); ?>
<?php
$zx_bayar_kredit_edit->ShowMessage();
?>
<form name="fzx_bayar_kreditedit" id="fzx_bayar_kreditedit" class="<?php echo $zx_bayar_kredit_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($zx_bayar_kredit_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $zx_bayar_kredit_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="zx_bayar_kredit">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($zx_bayar_kredit_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($zx_bayar_kredit->id_bayar->Visible) { // id_bayar ?>
	<div id="r_id_bayar" class="form-group">
		<label id="elh_zx_bayar_kredit_id_bayar" for="x_id_bayar" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->id_bayar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->id_bayar->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_id_bayar">
<span<?php echo $zx_bayar_kredit->id_bayar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $zx_bayar_kredit->id_bayar->EditValue ?></p></span>
</span>
<input type="hidden" data-table="zx_bayar_kredit" data-field="x_id_bayar" name="x_id_bayar" id="x_id_bayar" value="<?php echo ew_HtmlEncode($zx_bayar_kredit->id_bayar->CurrentValue) ?>">
<?php echo $zx_bayar_kredit->id_bayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_bayar_kredit->tgl_bayar->Visible) { // tgl_bayar ?>
	<div id="r_tgl_bayar" class="form-group">
		<label id="elh_zx_bayar_kredit_tgl_bayar" for="x_tgl_bayar" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->tgl_bayar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->tgl_bayar->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_tgl_bayar">
<input type="text" data-table="zx_bayar_kredit" data-field="x_tgl_bayar" name="x_tgl_bayar" id="x_tgl_bayar" placeholder="<?php echo ew_HtmlEncode($zx_bayar_kredit->tgl_bayar->getPlaceHolder()) ?>" value="<?php echo $zx_bayar_kredit->tgl_bayar->EditValue ?>"<?php echo $zx_bayar_kredit->tgl_bayar->EditAttributes() ?>>
</span>
<?php echo $zx_bayar_kredit->tgl_bayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_bayar_kredit->id_kredit->Visible) { // id_kredit ?>
	<div id="r_id_kredit" class="form-group">
		<label id="elh_zx_bayar_kredit_id_kredit" for="x_id_kredit" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->id_kredit->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->id_kredit->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_id_kredit">
<input type="text" data-table="zx_bayar_kredit" data-field="x_id_kredit" name="x_id_kredit" id="x_id_kredit" size="30" placeholder="<?php echo ew_HtmlEncode($zx_bayar_kredit->id_kredit->getPlaceHolder()) ?>" value="<?php echo $zx_bayar_kredit->id_kredit->EditValue ?>"<?php echo $zx_bayar_kredit->id_kredit->EditAttributes() ?>>
</span>
<?php echo $zx_bayar_kredit->id_kredit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_bayar_kredit->no_urut->Visible) { // no_urut ?>
	<div id="r_no_urut" class="form-group">
		<label id="elh_zx_bayar_kredit_no_urut" for="x_no_urut" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->no_urut->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->no_urut->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_no_urut">
<input type="text" data-table="zx_bayar_kredit" data-field="x_no_urut" name="x_no_urut" id="x_no_urut" size="30" placeholder="<?php echo ew_HtmlEncode($zx_bayar_kredit->no_urut->getPlaceHolder()) ?>" value="<?php echo $zx_bayar_kredit->no_urut->EditValue ?>"<?php echo $zx_bayar_kredit->no_urut->EditAttributes() ?>>
</span>
<?php echo $zx_bayar_kredit->no_urut->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_bayar_kredit->tgl_jt->Visible) { // tgl_jt ?>
	<div id="r_tgl_jt" class="form-group">
		<label id="elh_zx_bayar_kredit_tgl_jt" for="x_tgl_jt" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->tgl_jt->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->tgl_jt->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_tgl_jt">
<input type="text" data-table="zx_bayar_kredit" data-field="x_tgl_jt" name="x_tgl_jt" id="x_tgl_jt" placeholder="<?php echo ew_HtmlEncode($zx_bayar_kredit->tgl_jt->getPlaceHolder()) ?>" value="<?php echo $zx_bayar_kredit->tgl_jt->EditValue ?>"<?php echo $zx_bayar_kredit->tgl_jt->EditAttributes() ?>>
</span>
<?php echo $zx_bayar_kredit->tgl_jt->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_bayar_kredit->debet->Visible) { // debet ?>
	<div id="r_debet" class="form-group">
		<label id="elh_zx_bayar_kredit_debet" for="x_debet" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->debet->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->debet->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_debet">
<input type="text" data-table="zx_bayar_kredit" data-field="x_debet" name="x_debet" id="x_debet" size="30" placeholder="<?php echo ew_HtmlEncode($zx_bayar_kredit->debet->getPlaceHolder()) ?>" value="<?php echo $zx_bayar_kredit->debet->EditValue ?>"<?php echo $zx_bayar_kredit->debet->EditAttributes() ?>>
</span>
<?php echo $zx_bayar_kredit->debet->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_bayar_kredit->angs_pokok->Visible) { // angs_pokok ?>
	<div id="r_angs_pokok" class="form-group">
		<label id="elh_zx_bayar_kredit_angs_pokok" for="x_angs_pokok" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->angs_pokok->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->angs_pokok->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_angs_pokok">
<input type="text" data-table="zx_bayar_kredit" data-field="x_angs_pokok" name="x_angs_pokok" id="x_angs_pokok" size="30" placeholder="<?php echo ew_HtmlEncode($zx_bayar_kredit->angs_pokok->getPlaceHolder()) ?>" value="<?php echo $zx_bayar_kredit->angs_pokok->EditValue ?>"<?php echo $zx_bayar_kredit->angs_pokok->EditAttributes() ?>>
</span>
<?php echo $zx_bayar_kredit->angs_pokok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_bayar_kredit->bunga->Visible) { // bunga ?>
	<div id="r_bunga" class="form-group">
		<label id="elh_zx_bayar_kredit_bunga" for="x_bunga" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->bunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->bunga->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_bunga">
<input type="text" data-table="zx_bayar_kredit" data-field="x_bunga" name="x_bunga" id="x_bunga" size="30" placeholder="<?php echo ew_HtmlEncode($zx_bayar_kredit->bunga->getPlaceHolder()) ?>" value="<?php echo $zx_bayar_kredit->bunga->EditValue ?>"<?php echo $zx_bayar_kredit->bunga->EditAttributes() ?>>
</span>
<?php echo $zx_bayar_kredit->bunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_bayar_kredit->emp_id_auto->Visible) { // emp_id_auto ?>
	<div id="r_emp_id_auto" class="form-group">
		<label id="elh_zx_bayar_kredit_emp_id_auto" for="x_emp_id_auto" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->emp_id_auto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->emp_id_auto->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_emp_id_auto">
<input type="text" data-table="zx_bayar_kredit" data-field="x_emp_id_auto" name="x_emp_id_auto" id="x_emp_id_auto" size="30" placeholder="<?php echo ew_HtmlEncode($zx_bayar_kredit->emp_id_auto->getPlaceHolder()) ?>" value="<?php echo $zx_bayar_kredit->emp_id_auto->EditValue ?>"<?php echo $zx_bayar_kredit->emp_id_auto->EditAttributes() ?>>
</span>
<?php echo $zx_bayar_kredit->emp_id_auto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_bayar_kredit->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_zx_bayar_kredit_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->keterangan->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_keterangan">
<textarea data-table="zx_bayar_kredit" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($zx_bayar_kredit->keterangan->getPlaceHolder()) ?>"<?php echo $zx_bayar_kredit->keterangan->EditAttributes() ?>><?php echo $zx_bayar_kredit->keterangan->EditValue ?></textarea>
</span>
<?php echo $zx_bayar_kredit->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_bayar_kredit->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_zx_bayar_kredit_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->status->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_status">
<input type="text" data-table="zx_bayar_kredit" data-field="x_status" name="x_status" id="x_status" size="30" placeholder="<?php echo ew_HtmlEncode($zx_bayar_kredit->status->getPlaceHolder()) ?>" value="<?php echo $zx_bayar_kredit->status->EditValue ?>"<?php echo $zx_bayar_kredit->status->EditAttributes() ?>>
</span>
<?php echo $zx_bayar_kredit->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_bayar_kredit->lastupdate_date->Visible) { // lastupdate_date ?>
	<div id="r_lastupdate_date" class="form-group">
		<label id="elh_zx_bayar_kredit_lastupdate_date" for="x_lastupdate_date" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->lastupdate_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->lastupdate_date->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_lastupdate_date">
<input type="text" data-table="zx_bayar_kredit" data-field="x_lastupdate_date" name="x_lastupdate_date" id="x_lastupdate_date" placeholder="<?php echo ew_HtmlEncode($zx_bayar_kredit->lastupdate_date->getPlaceHolder()) ?>" value="<?php echo $zx_bayar_kredit->lastupdate_date->EditValue ?>"<?php echo $zx_bayar_kredit->lastupdate_date->EditAttributes() ?>>
</span>
<?php echo $zx_bayar_kredit->lastupdate_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_bayar_kredit->lastupdate_user->Visible) { // lastupdate_user ?>
	<div id="r_lastupdate_user" class="form-group">
		<label id="elh_zx_bayar_kredit_lastupdate_user" for="x_lastupdate_user" class="col-sm-2 control-label ewLabel"><?php echo $zx_bayar_kredit->lastupdate_user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_bayar_kredit->lastupdate_user->CellAttributes() ?>>
<span id="el_zx_bayar_kredit_lastupdate_user">
<input type="text" data-table="zx_bayar_kredit" data-field="x_lastupdate_user" name="x_lastupdate_user" id="x_lastupdate_user" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($zx_bayar_kredit->lastupdate_user->getPlaceHolder()) ?>" value="<?php echo $zx_bayar_kredit->lastupdate_user->EditValue ?>"<?php echo $zx_bayar_kredit->lastupdate_user->EditAttributes() ?>>
</span>
<?php echo $zx_bayar_kredit->lastupdate_user->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$zx_bayar_kredit_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $zx_bayar_kredit_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fzx_bayar_kreditedit.Init();
</script>
<?php
$zx_bayar_kredit_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$zx_bayar_kredit_edit->Page_Terminate();
?>
