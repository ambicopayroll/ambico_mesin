<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "z_pay_grp_cominfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$z_pay_grp_com_edit = NULL; // Initialize page object first

class cz_pay_grp_com_edit extends cz_pay_grp_com {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'z_pay_grp_com';

	// Page object name
	var $PageObjName = 'z_pay_grp_com_edit';

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

		// Table object (z_pay_grp_com)
		if (!isset($GLOBALS["z_pay_grp_com"]) || get_class($GLOBALS["z_pay_grp_com"]) == "cz_pay_grp_com") {
			$GLOBALS["z_pay_grp_com"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["z_pay_grp_com"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'z_pay_grp_com', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("z_pay_grp_comlist.php"));
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
		$this->grp_id->SetVisibility();
		$this->com_id->SetVisibility();
		$this->no_urut_ref->SetVisibility();
		$this->use_if_sum->SetVisibility();
		$this->use_kode_if->SetVisibility();
		$this->id_kode_if->SetVisibility();
		$this->min_if->SetVisibility();
		$this->max_if->SetVisibility();
		$this->use_sum->SetVisibility();
		$this->use_kode_sum->SetVisibility();
		$this->id_kode_sum->SetVisibility();
		$this->operator_sum->SetVisibility();
		$this->konstanta_sum->SetVisibility();
		$this->operator_sum2->SetVisibility();
		$this->nilai_rp->SetVisibility();
		$this->hitung->SetVisibility();
		$this->jenis->SetVisibility();

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
		global $EW_EXPORT, $z_pay_grp_com;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($z_pay_grp_com);
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
		if (@$_GET["grp_id"] <> "") {
			$this->grp_id->setQueryStringValue($_GET["grp_id"]);
		}
		if (@$_GET["com_id"] <> "") {
			$this->com_id->setQueryStringValue($_GET["com_id"]);
		}
		if (@$_GET["no_urut_ref"] <> "") {
			$this->no_urut_ref->setQueryStringValue($_GET["no_urut_ref"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->grp_id->CurrentValue == "") {
			$this->Page_Terminate("z_pay_grp_comlist.php"); // Invalid key, return to list
		}
		if ($this->com_id->CurrentValue == "") {
			$this->Page_Terminate("z_pay_grp_comlist.php"); // Invalid key, return to list
		}
		if ($this->no_urut_ref->CurrentValue == "") {
			$this->Page_Terminate("z_pay_grp_comlist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("z_pay_grp_comlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "z_pay_grp_comlist.php")
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
		if (!$this->grp_id->FldIsDetailKey) {
			$this->grp_id->setFormValue($objForm->GetValue("x_grp_id"));
		}
		if (!$this->com_id->FldIsDetailKey) {
			$this->com_id->setFormValue($objForm->GetValue("x_com_id"));
		}
		if (!$this->no_urut_ref->FldIsDetailKey) {
			$this->no_urut_ref->setFormValue($objForm->GetValue("x_no_urut_ref"));
		}
		if (!$this->use_if_sum->FldIsDetailKey) {
			$this->use_if_sum->setFormValue($objForm->GetValue("x_use_if_sum"));
		}
		if (!$this->use_kode_if->FldIsDetailKey) {
			$this->use_kode_if->setFormValue($objForm->GetValue("x_use_kode_if"));
		}
		if (!$this->id_kode_if->FldIsDetailKey) {
			$this->id_kode_if->setFormValue($objForm->GetValue("x_id_kode_if"));
		}
		if (!$this->min_if->FldIsDetailKey) {
			$this->min_if->setFormValue($objForm->GetValue("x_min_if"));
		}
		if (!$this->max_if->FldIsDetailKey) {
			$this->max_if->setFormValue($objForm->GetValue("x_max_if"));
		}
		if (!$this->use_sum->FldIsDetailKey) {
			$this->use_sum->setFormValue($objForm->GetValue("x_use_sum"));
		}
		if (!$this->use_kode_sum->FldIsDetailKey) {
			$this->use_kode_sum->setFormValue($objForm->GetValue("x_use_kode_sum"));
		}
		if (!$this->id_kode_sum->FldIsDetailKey) {
			$this->id_kode_sum->setFormValue($objForm->GetValue("x_id_kode_sum"));
		}
		if (!$this->operator_sum->FldIsDetailKey) {
			$this->operator_sum->setFormValue($objForm->GetValue("x_operator_sum"));
		}
		if (!$this->konstanta_sum->FldIsDetailKey) {
			$this->konstanta_sum->setFormValue($objForm->GetValue("x_konstanta_sum"));
		}
		if (!$this->operator_sum2->FldIsDetailKey) {
			$this->operator_sum2->setFormValue($objForm->GetValue("x_operator_sum2"));
		}
		if (!$this->nilai_rp->FldIsDetailKey) {
			$this->nilai_rp->setFormValue($objForm->GetValue("x_nilai_rp"));
		}
		if (!$this->hitung->FldIsDetailKey) {
			$this->hitung->setFormValue($objForm->GetValue("x_hitung"));
		}
		if (!$this->jenis->FldIsDetailKey) {
			$this->jenis->setFormValue($objForm->GetValue("x_jenis"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->grp_id->CurrentValue = $this->grp_id->FormValue;
		$this->com_id->CurrentValue = $this->com_id->FormValue;
		$this->no_urut_ref->CurrentValue = $this->no_urut_ref->FormValue;
		$this->use_if_sum->CurrentValue = $this->use_if_sum->FormValue;
		$this->use_kode_if->CurrentValue = $this->use_kode_if->FormValue;
		$this->id_kode_if->CurrentValue = $this->id_kode_if->FormValue;
		$this->min_if->CurrentValue = $this->min_if->FormValue;
		$this->max_if->CurrentValue = $this->max_if->FormValue;
		$this->use_sum->CurrentValue = $this->use_sum->FormValue;
		$this->use_kode_sum->CurrentValue = $this->use_kode_sum->FormValue;
		$this->id_kode_sum->CurrentValue = $this->id_kode_sum->FormValue;
		$this->operator_sum->CurrentValue = $this->operator_sum->FormValue;
		$this->konstanta_sum->CurrentValue = $this->konstanta_sum->FormValue;
		$this->operator_sum2->CurrentValue = $this->operator_sum2->FormValue;
		$this->nilai_rp->CurrentValue = $this->nilai_rp->FormValue;
		$this->hitung->CurrentValue = $this->hitung->FormValue;
		$this->jenis->CurrentValue = $this->jenis->FormValue;
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
		$this->grp_id->setDbValue($rs->fields('grp_id'));
		$this->com_id->setDbValue($rs->fields('com_id'));
		$this->no_urut_ref->setDbValue($rs->fields('no_urut_ref'));
		$this->use_if_sum->setDbValue($rs->fields('use_if_sum'));
		$this->use_kode_if->setDbValue($rs->fields('use_kode_if'));
		$this->id_kode_if->setDbValue($rs->fields('id_kode_if'));
		$this->min_if->setDbValue($rs->fields('min_if'));
		$this->max_if->setDbValue($rs->fields('max_if'));
		$this->use_sum->setDbValue($rs->fields('use_sum'));
		$this->use_kode_sum->setDbValue($rs->fields('use_kode_sum'));
		$this->id_kode_sum->setDbValue($rs->fields('id_kode_sum'));
		$this->operator_sum->setDbValue($rs->fields('operator_sum'));
		$this->konstanta_sum->setDbValue($rs->fields('konstanta_sum'));
		$this->operator_sum2->setDbValue($rs->fields('operator_sum2'));
		$this->nilai_rp->setDbValue($rs->fields('nilai_rp'));
		$this->hitung->setDbValue($rs->fields('hitung'));
		$this->jenis->setDbValue($rs->fields('jenis'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->grp_id->DbValue = $row['grp_id'];
		$this->com_id->DbValue = $row['com_id'];
		$this->no_urut_ref->DbValue = $row['no_urut_ref'];
		$this->use_if_sum->DbValue = $row['use_if_sum'];
		$this->use_kode_if->DbValue = $row['use_kode_if'];
		$this->id_kode_if->DbValue = $row['id_kode_if'];
		$this->min_if->DbValue = $row['min_if'];
		$this->max_if->DbValue = $row['max_if'];
		$this->use_sum->DbValue = $row['use_sum'];
		$this->use_kode_sum->DbValue = $row['use_kode_sum'];
		$this->id_kode_sum->DbValue = $row['id_kode_sum'];
		$this->operator_sum->DbValue = $row['operator_sum'];
		$this->konstanta_sum->DbValue = $row['konstanta_sum'];
		$this->operator_sum2->DbValue = $row['operator_sum2'];
		$this->nilai_rp->DbValue = $row['nilai_rp'];
		$this->hitung->DbValue = $row['hitung'];
		$this->jenis->DbValue = $row['jenis'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->min_if->FormValue == $this->min_if->CurrentValue && is_numeric(ew_StrToFloat($this->min_if->CurrentValue)))
			$this->min_if->CurrentValue = ew_StrToFloat($this->min_if->CurrentValue);

		// Convert decimal values if posted back
		if ($this->max_if->FormValue == $this->max_if->CurrentValue && is_numeric(ew_StrToFloat($this->max_if->CurrentValue)))
			$this->max_if->CurrentValue = ew_StrToFloat($this->max_if->CurrentValue);

		// Convert decimal values if posted back
		if ($this->konstanta_sum->FormValue == $this->konstanta_sum->CurrentValue && is_numeric(ew_StrToFloat($this->konstanta_sum->CurrentValue)))
			$this->konstanta_sum->CurrentValue = ew_StrToFloat($this->konstanta_sum->CurrentValue);

		// Convert decimal values if posted back
		if ($this->nilai_rp->FormValue == $this->nilai_rp->CurrentValue && is_numeric(ew_StrToFloat($this->nilai_rp->CurrentValue)))
			$this->nilai_rp->CurrentValue = ew_StrToFloat($this->nilai_rp->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// grp_id
		// com_id
		// no_urut_ref
		// use_if_sum
		// use_kode_if
		// id_kode_if
		// min_if
		// max_if
		// use_sum
		// use_kode_sum
		// id_kode_sum
		// operator_sum
		// konstanta_sum
		// operator_sum2
		// nilai_rp
		// hitung
		// jenis

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// grp_id
		$this->grp_id->ViewValue = $this->grp_id->CurrentValue;
		$this->grp_id->ViewCustomAttributes = "";

		// com_id
		$this->com_id->ViewValue = $this->com_id->CurrentValue;
		$this->com_id->ViewCustomAttributes = "";

		// no_urut_ref
		$this->no_urut_ref->ViewValue = $this->no_urut_ref->CurrentValue;
		$this->no_urut_ref->ViewCustomAttributes = "";

		// use_if_sum
		$this->use_if_sum->ViewValue = $this->use_if_sum->CurrentValue;
		$this->use_if_sum->ViewCustomAttributes = "";

		// use_kode_if
		$this->use_kode_if->ViewValue = $this->use_kode_if->CurrentValue;
		$this->use_kode_if->ViewCustomAttributes = "";

		// id_kode_if
		$this->id_kode_if->ViewValue = $this->id_kode_if->CurrentValue;
		$this->id_kode_if->ViewCustomAttributes = "";

		// min_if
		$this->min_if->ViewValue = $this->min_if->CurrentValue;
		$this->min_if->ViewCustomAttributes = "";

		// max_if
		$this->max_if->ViewValue = $this->max_if->CurrentValue;
		$this->max_if->ViewCustomAttributes = "";

		// use_sum
		$this->use_sum->ViewValue = $this->use_sum->CurrentValue;
		$this->use_sum->ViewCustomAttributes = "";

		// use_kode_sum
		$this->use_kode_sum->ViewValue = $this->use_kode_sum->CurrentValue;
		$this->use_kode_sum->ViewCustomAttributes = "";

		// id_kode_sum
		$this->id_kode_sum->ViewValue = $this->id_kode_sum->CurrentValue;
		$this->id_kode_sum->ViewCustomAttributes = "";

		// operator_sum
		$this->operator_sum->ViewValue = $this->operator_sum->CurrentValue;
		$this->operator_sum->ViewCustomAttributes = "";

		// konstanta_sum
		$this->konstanta_sum->ViewValue = $this->konstanta_sum->CurrentValue;
		$this->konstanta_sum->ViewCustomAttributes = "";

		// operator_sum2
		$this->operator_sum2->ViewValue = $this->operator_sum2->CurrentValue;
		$this->operator_sum2->ViewCustomAttributes = "";

		// nilai_rp
		$this->nilai_rp->ViewValue = $this->nilai_rp->CurrentValue;
		$this->nilai_rp->ViewCustomAttributes = "";

		// hitung
		$this->hitung->ViewValue = $this->hitung->CurrentValue;
		$this->hitung->ViewCustomAttributes = "";

		// jenis
		$this->jenis->ViewValue = $this->jenis->CurrentValue;
		$this->jenis->ViewCustomAttributes = "";

			// grp_id
			$this->grp_id->LinkCustomAttributes = "";
			$this->grp_id->HrefValue = "";
			$this->grp_id->TooltipValue = "";

			// com_id
			$this->com_id->LinkCustomAttributes = "";
			$this->com_id->HrefValue = "";
			$this->com_id->TooltipValue = "";

			// no_urut_ref
			$this->no_urut_ref->LinkCustomAttributes = "";
			$this->no_urut_ref->HrefValue = "";
			$this->no_urut_ref->TooltipValue = "";

			// use_if_sum
			$this->use_if_sum->LinkCustomAttributes = "";
			$this->use_if_sum->HrefValue = "";
			$this->use_if_sum->TooltipValue = "";

			// use_kode_if
			$this->use_kode_if->LinkCustomAttributes = "";
			$this->use_kode_if->HrefValue = "";
			$this->use_kode_if->TooltipValue = "";

			// id_kode_if
			$this->id_kode_if->LinkCustomAttributes = "";
			$this->id_kode_if->HrefValue = "";
			$this->id_kode_if->TooltipValue = "";

			// min_if
			$this->min_if->LinkCustomAttributes = "";
			$this->min_if->HrefValue = "";
			$this->min_if->TooltipValue = "";

			// max_if
			$this->max_if->LinkCustomAttributes = "";
			$this->max_if->HrefValue = "";
			$this->max_if->TooltipValue = "";

			// use_sum
			$this->use_sum->LinkCustomAttributes = "";
			$this->use_sum->HrefValue = "";
			$this->use_sum->TooltipValue = "";

			// use_kode_sum
			$this->use_kode_sum->LinkCustomAttributes = "";
			$this->use_kode_sum->HrefValue = "";
			$this->use_kode_sum->TooltipValue = "";

			// id_kode_sum
			$this->id_kode_sum->LinkCustomAttributes = "";
			$this->id_kode_sum->HrefValue = "";
			$this->id_kode_sum->TooltipValue = "";

			// operator_sum
			$this->operator_sum->LinkCustomAttributes = "";
			$this->operator_sum->HrefValue = "";
			$this->operator_sum->TooltipValue = "";

			// konstanta_sum
			$this->konstanta_sum->LinkCustomAttributes = "";
			$this->konstanta_sum->HrefValue = "";
			$this->konstanta_sum->TooltipValue = "";

			// operator_sum2
			$this->operator_sum2->LinkCustomAttributes = "";
			$this->operator_sum2->HrefValue = "";
			$this->operator_sum2->TooltipValue = "";

			// nilai_rp
			$this->nilai_rp->LinkCustomAttributes = "";
			$this->nilai_rp->HrefValue = "";
			$this->nilai_rp->TooltipValue = "";

			// hitung
			$this->hitung->LinkCustomAttributes = "";
			$this->hitung->HrefValue = "";
			$this->hitung->TooltipValue = "";

			// jenis
			$this->jenis->LinkCustomAttributes = "";
			$this->jenis->HrefValue = "";
			$this->jenis->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// grp_id
			$this->grp_id->EditAttrs["class"] = "form-control";
			$this->grp_id->EditCustomAttributes = "";
			$this->grp_id->EditValue = $this->grp_id->CurrentValue;
			$this->grp_id->ViewCustomAttributes = "";

			// com_id
			$this->com_id->EditAttrs["class"] = "form-control";
			$this->com_id->EditCustomAttributes = "";
			$this->com_id->EditValue = $this->com_id->CurrentValue;
			$this->com_id->ViewCustomAttributes = "";

			// no_urut_ref
			$this->no_urut_ref->EditAttrs["class"] = "form-control";
			$this->no_urut_ref->EditCustomAttributes = "";
			$this->no_urut_ref->EditValue = $this->no_urut_ref->CurrentValue;
			$this->no_urut_ref->ViewCustomAttributes = "";

			// use_if_sum
			$this->use_if_sum->EditAttrs["class"] = "form-control";
			$this->use_if_sum->EditCustomAttributes = "";
			$this->use_if_sum->EditValue = ew_HtmlEncode($this->use_if_sum->CurrentValue);
			$this->use_if_sum->PlaceHolder = ew_RemoveHtml($this->use_if_sum->FldCaption());

			// use_kode_if
			$this->use_kode_if->EditAttrs["class"] = "form-control";
			$this->use_kode_if->EditCustomAttributes = "";
			$this->use_kode_if->EditValue = ew_HtmlEncode($this->use_kode_if->CurrentValue);
			$this->use_kode_if->PlaceHolder = ew_RemoveHtml($this->use_kode_if->FldCaption());

			// id_kode_if
			$this->id_kode_if->EditAttrs["class"] = "form-control";
			$this->id_kode_if->EditCustomAttributes = "";
			$this->id_kode_if->EditValue = ew_HtmlEncode($this->id_kode_if->CurrentValue);
			$this->id_kode_if->PlaceHolder = ew_RemoveHtml($this->id_kode_if->FldCaption());

			// min_if
			$this->min_if->EditAttrs["class"] = "form-control";
			$this->min_if->EditCustomAttributes = "";
			$this->min_if->EditValue = ew_HtmlEncode($this->min_if->CurrentValue);
			$this->min_if->PlaceHolder = ew_RemoveHtml($this->min_if->FldCaption());
			if (strval($this->min_if->EditValue) <> "" && is_numeric($this->min_if->EditValue)) $this->min_if->EditValue = ew_FormatNumber($this->min_if->EditValue, -2, -1, -2, 0);

			// max_if
			$this->max_if->EditAttrs["class"] = "form-control";
			$this->max_if->EditCustomAttributes = "";
			$this->max_if->EditValue = ew_HtmlEncode($this->max_if->CurrentValue);
			$this->max_if->PlaceHolder = ew_RemoveHtml($this->max_if->FldCaption());
			if (strval($this->max_if->EditValue) <> "" && is_numeric($this->max_if->EditValue)) $this->max_if->EditValue = ew_FormatNumber($this->max_if->EditValue, -2, -1, -2, 0);

			// use_sum
			$this->use_sum->EditAttrs["class"] = "form-control";
			$this->use_sum->EditCustomAttributes = "";
			$this->use_sum->EditValue = ew_HtmlEncode($this->use_sum->CurrentValue);
			$this->use_sum->PlaceHolder = ew_RemoveHtml($this->use_sum->FldCaption());

			// use_kode_sum
			$this->use_kode_sum->EditAttrs["class"] = "form-control";
			$this->use_kode_sum->EditCustomAttributes = "";
			$this->use_kode_sum->EditValue = ew_HtmlEncode($this->use_kode_sum->CurrentValue);
			$this->use_kode_sum->PlaceHolder = ew_RemoveHtml($this->use_kode_sum->FldCaption());

			// id_kode_sum
			$this->id_kode_sum->EditAttrs["class"] = "form-control";
			$this->id_kode_sum->EditCustomAttributes = "";
			$this->id_kode_sum->EditValue = ew_HtmlEncode($this->id_kode_sum->CurrentValue);
			$this->id_kode_sum->PlaceHolder = ew_RemoveHtml($this->id_kode_sum->FldCaption());

			// operator_sum
			$this->operator_sum->EditAttrs["class"] = "form-control";
			$this->operator_sum->EditCustomAttributes = "";
			$this->operator_sum->EditValue = ew_HtmlEncode($this->operator_sum->CurrentValue);
			$this->operator_sum->PlaceHolder = ew_RemoveHtml($this->operator_sum->FldCaption());

			// konstanta_sum
			$this->konstanta_sum->EditAttrs["class"] = "form-control";
			$this->konstanta_sum->EditCustomAttributes = "";
			$this->konstanta_sum->EditValue = ew_HtmlEncode($this->konstanta_sum->CurrentValue);
			$this->konstanta_sum->PlaceHolder = ew_RemoveHtml($this->konstanta_sum->FldCaption());
			if (strval($this->konstanta_sum->EditValue) <> "" && is_numeric($this->konstanta_sum->EditValue)) $this->konstanta_sum->EditValue = ew_FormatNumber($this->konstanta_sum->EditValue, -2, -1, -2, 0);

			// operator_sum2
			$this->operator_sum2->EditAttrs["class"] = "form-control";
			$this->operator_sum2->EditCustomAttributes = "";
			$this->operator_sum2->EditValue = ew_HtmlEncode($this->operator_sum2->CurrentValue);
			$this->operator_sum2->PlaceHolder = ew_RemoveHtml($this->operator_sum2->FldCaption());

			// nilai_rp
			$this->nilai_rp->EditAttrs["class"] = "form-control";
			$this->nilai_rp->EditCustomAttributes = "";
			$this->nilai_rp->EditValue = ew_HtmlEncode($this->nilai_rp->CurrentValue);
			$this->nilai_rp->PlaceHolder = ew_RemoveHtml($this->nilai_rp->FldCaption());
			if (strval($this->nilai_rp->EditValue) <> "" && is_numeric($this->nilai_rp->EditValue)) $this->nilai_rp->EditValue = ew_FormatNumber($this->nilai_rp->EditValue, -2, -1, -2, 0);

			// hitung
			$this->hitung->EditAttrs["class"] = "form-control";
			$this->hitung->EditCustomAttributes = "";
			$this->hitung->EditValue = ew_HtmlEncode($this->hitung->CurrentValue);
			$this->hitung->PlaceHolder = ew_RemoveHtml($this->hitung->FldCaption());

			// jenis
			$this->jenis->EditAttrs["class"] = "form-control";
			$this->jenis->EditCustomAttributes = "";
			$this->jenis->EditValue = ew_HtmlEncode($this->jenis->CurrentValue);
			$this->jenis->PlaceHolder = ew_RemoveHtml($this->jenis->FldCaption());

			// Edit refer script
			// grp_id

			$this->grp_id->LinkCustomAttributes = "";
			$this->grp_id->HrefValue = "";

			// com_id
			$this->com_id->LinkCustomAttributes = "";
			$this->com_id->HrefValue = "";

			// no_urut_ref
			$this->no_urut_ref->LinkCustomAttributes = "";
			$this->no_urut_ref->HrefValue = "";

			// use_if_sum
			$this->use_if_sum->LinkCustomAttributes = "";
			$this->use_if_sum->HrefValue = "";

			// use_kode_if
			$this->use_kode_if->LinkCustomAttributes = "";
			$this->use_kode_if->HrefValue = "";

			// id_kode_if
			$this->id_kode_if->LinkCustomAttributes = "";
			$this->id_kode_if->HrefValue = "";

			// min_if
			$this->min_if->LinkCustomAttributes = "";
			$this->min_if->HrefValue = "";

			// max_if
			$this->max_if->LinkCustomAttributes = "";
			$this->max_if->HrefValue = "";

			// use_sum
			$this->use_sum->LinkCustomAttributes = "";
			$this->use_sum->HrefValue = "";

			// use_kode_sum
			$this->use_kode_sum->LinkCustomAttributes = "";
			$this->use_kode_sum->HrefValue = "";

			// id_kode_sum
			$this->id_kode_sum->LinkCustomAttributes = "";
			$this->id_kode_sum->HrefValue = "";

			// operator_sum
			$this->operator_sum->LinkCustomAttributes = "";
			$this->operator_sum->HrefValue = "";

			// konstanta_sum
			$this->konstanta_sum->LinkCustomAttributes = "";
			$this->konstanta_sum->HrefValue = "";

			// operator_sum2
			$this->operator_sum2->LinkCustomAttributes = "";
			$this->operator_sum2->HrefValue = "";

			// nilai_rp
			$this->nilai_rp->LinkCustomAttributes = "";
			$this->nilai_rp->HrefValue = "";

			// hitung
			$this->hitung->LinkCustomAttributes = "";
			$this->hitung->HrefValue = "";

			// jenis
			$this->jenis->LinkCustomAttributes = "";
			$this->jenis->HrefValue = "";
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
		if (!$this->grp_id->FldIsDetailKey && !is_null($this->grp_id->FormValue) && $this->grp_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->grp_id->FldCaption(), $this->grp_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->grp_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->grp_id->FldErrMsg());
		}
		if (!$this->com_id->FldIsDetailKey && !is_null($this->com_id->FormValue) && $this->com_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->com_id->FldCaption(), $this->com_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->com_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->com_id->FldErrMsg());
		}
		if (!$this->no_urut_ref->FldIsDetailKey && !is_null($this->no_urut_ref->FormValue) && $this->no_urut_ref->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->no_urut_ref->FldCaption(), $this->no_urut_ref->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->no_urut_ref->FormValue)) {
			ew_AddMessage($gsFormError, $this->no_urut_ref->FldErrMsg());
		}
		if (!$this->use_if_sum->FldIsDetailKey && !is_null($this->use_if_sum->FormValue) && $this->use_if_sum->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->use_if_sum->FldCaption(), $this->use_if_sum->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->use_if_sum->FormValue)) {
			ew_AddMessage($gsFormError, $this->use_if_sum->FldErrMsg());
		}
		if (!$this->use_kode_if->FldIsDetailKey && !is_null($this->use_kode_if->FormValue) && $this->use_kode_if->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->use_kode_if->FldCaption(), $this->use_kode_if->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->use_kode_if->FormValue)) {
			ew_AddMessage($gsFormError, $this->use_kode_if->FldErrMsg());
		}
		if (!$this->id_kode_if->FldIsDetailKey && !is_null($this->id_kode_if->FormValue) && $this->id_kode_if->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_kode_if->FldCaption(), $this->id_kode_if->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id_kode_if->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_kode_if->FldErrMsg());
		}
		if (!$this->min_if->FldIsDetailKey && !is_null($this->min_if->FormValue) && $this->min_if->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->min_if->FldCaption(), $this->min_if->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->min_if->FormValue)) {
			ew_AddMessage($gsFormError, $this->min_if->FldErrMsg());
		}
		if (!$this->max_if->FldIsDetailKey && !is_null($this->max_if->FormValue) && $this->max_if->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->max_if->FldCaption(), $this->max_if->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->max_if->FormValue)) {
			ew_AddMessage($gsFormError, $this->max_if->FldErrMsg());
		}
		if (!$this->use_sum->FldIsDetailKey && !is_null($this->use_sum->FormValue) && $this->use_sum->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->use_sum->FldCaption(), $this->use_sum->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->use_sum->FormValue)) {
			ew_AddMessage($gsFormError, $this->use_sum->FldErrMsg());
		}
		if (!$this->use_kode_sum->FldIsDetailKey && !is_null($this->use_kode_sum->FormValue) && $this->use_kode_sum->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->use_kode_sum->FldCaption(), $this->use_kode_sum->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->use_kode_sum->FormValue)) {
			ew_AddMessage($gsFormError, $this->use_kode_sum->FldErrMsg());
		}
		if (!$this->id_kode_sum->FldIsDetailKey && !is_null($this->id_kode_sum->FormValue) && $this->id_kode_sum->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_kode_sum->FldCaption(), $this->id_kode_sum->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id_kode_sum->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_kode_sum->FldErrMsg());
		}
		if (!$this->operator_sum->FldIsDetailKey && !is_null($this->operator_sum->FormValue) && $this->operator_sum->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->operator_sum->FldCaption(), $this->operator_sum->ReqErrMsg));
		}
		if (!$this->konstanta_sum->FldIsDetailKey && !is_null($this->konstanta_sum->FormValue) && $this->konstanta_sum->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->konstanta_sum->FldCaption(), $this->konstanta_sum->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->konstanta_sum->FormValue)) {
			ew_AddMessage($gsFormError, $this->konstanta_sum->FldErrMsg());
		}
		if (!$this->operator_sum2->FldIsDetailKey && !is_null($this->operator_sum2->FormValue) && $this->operator_sum2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->operator_sum2->FldCaption(), $this->operator_sum2->ReqErrMsg));
		}
		if (!$this->nilai_rp->FldIsDetailKey && !is_null($this->nilai_rp->FormValue) && $this->nilai_rp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nilai_rp->FldCaption(), $this->nilai_rp->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->nilai_rp->FormValue)) {
			ew_AddMessage($gsFormError, $this->nilai_rp->FldErrMsg());
		}
		if (!$this->hitung->FldIsDetailKey && !is_null($this->hitung->FormValue) && $this->hitung->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hitung->FldCaption(), $this->hitung->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->hitung->FormValue)) {
			ew_AddMessage($gsFormError, $this->hitung->FldErrMsg());
		}
		if (!$this->jenis->FldIsDetailKey && !is_null($this->jenis->FormValue) && $this->jenis->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jenis->FldCaption(), $this->jenis->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jenis->FormValue)) {
			ew_AddMessage($gsFormError, $this->jenis->FldErrMsg());
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

			// grp_id
			// com_id
			// no_urut_ref
			// use_if_sum

			$this->use_if_sum->SetDbValueDef($rsnew, $this->use_if_sum->CurrentValue, 0, $this->use_if_sum->ReadOnly);

			// use_kode_if
			$this->use_kode_if->SetDbValueDef($rsnew, $this->use_kode_if->CurrentValue, 0, $this->use_kode_if->ReadOnly);

			// id_kode_if
			$this->id_kode_if->SetDbValueDef($rsnew, $this->id_kode_if->CurrentValue, 0, $this->id_kode_if->ReadOnly);

			// min_if
			$this->min_if->SetDbValueDef($rsnew, $this->min_if->CurrentValue, 0, $this->min_if->ReadOnly);

			// max_if
			$this->max_if->SetDbValueDef($rsnew, $this->max_if->CurrentValue, 0, $this->max_if->ReadOnly);

			// use_sum
			$this->use_sum->SetDbValueDef($rsnew, $this->use_sum->CurrentValue, 0, $this->use_sum->ReadOnly);

			// use_kode_sum
			$this->use_kode_sum->SetDbValueDef($rsnew, $this->use_kode_sum->CurrentValue, 0, $this->use_kode_sum->ReadOnly);

			// id_kode_sum
			$this->id_kode_sum->SetDbValueDef($rsnew, $this->id_kode_sum->CurrentValue, 0, $this->id_kode_sum->ReadOnly);

			// operator_sum
			$this->operator_sum->SetDbValueDef($rsnew, $this->operator_sum->CurrentValue, "", $this->operator_sum->ReadOnly);

			// konstanta_sum
			$this->konstanta_sum->SetDbValueDef($rsnew, $this->konstanta_sum->CurrentValue, 0, $this->konstanta_sum->ReadOnly);

			// operator_sum2
			$this->operator_sum2->SetDbValueDef($rsnew, $this->operator_sum2->CurrentValue, "", $this->operator_sum2->ReadOnly);

			// nilai_rp
			$this->nilai_rp->SetDbValueDef($rsnew, $this->nilai_rp->CurrentValue, 0, $this->nilai_rp->ReadOnly);

			// hitung
			$this->hitung->SetDbValueDef($rsnew, $this->hitung->CurrentValue, 0, $this->hitung->ReadOnly);

			// jenis
			$this->jenis->SetDbValueDef($rsnew, $this->jenis->CurrentValue, 0, $this->jenis->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("z_pay_grp_comlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($z_pay_grp_com_edit)) $z_pay_grp_com_edit = new cz_pay_grp_com_edit();

// Page init
$z_pay_grp_com_edit->Page_Init();

// Page main
$z_pay_grp_com_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$z_pay_grp_com_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fz_pay_grp_comedit = new ew_Form("fz_pay_grp_comedit", "edit");

// Validate form
fz_pay_grp_comedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_grp_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->grp_id->FldCaption(), $z_pay_grp_com->grp_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_grp_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->grp_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_com_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->com_id->FldCaption(), $z_pay_grp_com->com_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_com_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->com_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_no_urut_ref");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->no_urut_ref->FldCaption(), $z_pay_grp_com->no_urut_ref->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_no_urut_ref");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->no_urut_ref->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_use_if_sum");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->use_if_sum->FldCaption(), $z_pay_grp_com->use_if_sum->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_use_if_sum");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->use_if_sum->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_use_kode_if");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->use_kode_if->FldCaption(), $z_pay_grp_com->use_kode_if->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_use_kode_if");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->use_kode_if->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_kode_if");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->id_kode_if->FldCaption(), $z_pay_grp_com->id_kode_if->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_kode_if");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->id_kode_if->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_min_if");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->min_if->FldCaption(), $z_pay_grp_com->min_if->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_min_if");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->min_if->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_max_if");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->max_if->FldCaption(), $z_pay_grp_com->max_if->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_max_if");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->max_if->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_use_sum");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->use_sum->FldCaption(), $z_pay_grp_com->use_sum->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_use_sum");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->use_sum->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_use_kode_sum");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->use_kode_sum->FldCaption(), $z_pay_grp_com->use_kode_sum->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_use_kode_sum");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->use_kode_sum->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_kode_sum");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->id_kode_sum->FldCaption(), $z_pay_grp_com->id_kode_sum->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_kode_sum");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->id_kode_sum->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_operator_sum");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->operator_sum->FldCaption(), $z_pay_grp_com->operator_sum->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_konstanta_sum");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->konstanta_sum->FldCaption(), $z_pay_grp_com->konstanta_sum->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_konstanta_sum");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->konstanta_sum->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_operator_sum2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->operator_sum2->FldCaption(), $z_pay_grp_com->operator_sum2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nilai_rp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->nilai_rp->FldCaption(), $z_pay_grp_com->nilai_rp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nilai_rp");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->nilai_rp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hitung");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->hitung->FldCaption(), $z_pay_grp_com->hitung->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hitung");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->hitung->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jenis");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp_com->jenis->FldCaption(), $z_pay_grp_com->jenis->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenis");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp_com->jenis->FldErrMsg()) ?>");

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
fz_pay_grp_comedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fz_pay_grp_comedit.ValidateRequired = true;
<?php } else { ?>
fz_pay_grp_comedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$z_pay_grp_com_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $z_pay_grp_com_edit->ShowPageHeader(); ?>
<?php
$z_pay_grp_com_edit->ShowMessage();
?>
<form name="fz_pay_grp_comedit" id="fz_pay_grp_comedit" class="<?php echo $z_pay_grp_com_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($z_pay_grp_com_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $z_pay_grp_com_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="z_pay_grp_com">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($z_pay_grp_com_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($z_pay_grp_com->grp_id->Visible) { // grp_id ?>
	<div id="r_grp_id" class="form-group">
		<label id="elh_z_pay_grp_com_grp_id" for="x_grp_id" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->grp_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->grp_id->CellAttributes() ?>>
<span id="el_z_pay_grp_com_grp_id">
<span<?php echo $z_pay_grp_com->grp_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $z_pay_grp_com->grp_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="z_pay_grp_com" data-field="x_grp_id" name="x_grp_id" id="x_grp_id" value="<?php echo ew_HtmlEncode($z_pay_grp_com->grp_id->CurrentValue) ?>">
<?php echo $z_pay_grp_com->grp_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->com_id->Visible) { // com_id ?>
	<div id="r_com_id" class="form-group">
		<label id="elh_z_pay_grp_com_com_id" for="x_com_id" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->com_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->com_id->CellAttributes() ?>>
<span id="el_z_pay_grp_com_com_id">
<span<?php echo $z_pay_grp_com->com_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $z_pay_grp_com->com_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="z_pay_grp_com" data-field="x_com_id" name="x_com_id" id="x_com_id" value="<?php echo ew_HtmlEncode($z_pay_grp_com->com_id->CurrentValue) ?>">
<?php echo $z_pay_grp_com->com_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->no_urut_ref->Visible) { // no_urut_ref ?>
	<div id="r_no_urut_ref" class="form-group">
		<label id="elh_z_pay_grp_com_no_urut_ref" for="x_no_urut_ref" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->no_urut_ref->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->no_urut_ref->CellAttributes() ?>>
<span id="el_z_pay_grp_com_no_urut_ref">
<span<?php echo $z_pay_grp_com->no_urut_ref->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $z_pay_grp_com->no_urut_ref->EditValue ?></p></span>
</span>
<input type="hidden" data-table="z_pay_grp_com" data-field="x_no_urut_ref" name="x_no_urut_ref" id="x_no_urut_ref" value="<?php echo ew_HtmlEncode($z_pay_grp_com->no_urut_ref->CurrentValue) ?>">
<?php echo $z_pay_grp_com->no_urut_ref->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->use_if_sum->Visible) { // use_if_sum ?>
	<div id="r_use_if_sum" class="form-group">
		<label id="elh_z_pay_grp_com_use_if_sum" for="x_use_if_sum" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->use_if_sum->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->use_if_sum->CellAttributes() ?>>
<span id="el_z_pay_grp_com_use_if_sum">
<input type="text" data-table="z_pay_grp_com" data-field="x_use_if_sum" name="x_use_if_sum" id="x_use_if_sum" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->use_if_sum->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->use_if_sum->EditValue ?>"<?php echo $z_pay_grp_com->use_if_sum->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->use_if_sum->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->use_kode_if->Visible) { // use_kode_if ?>
	<div id="r_use_kode_if" class="form-group">
		<label id="elh_z_pay_grp_com_use_kode_if" for="x_use_kode_if" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->use_kode_if->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->use_kode_if->CellAttributes() ?>>
<span id="el_z_pay_grp_com_use_kode_if">
<input type="text" data-table="z_pay_grp_com" data-field="x_use_kode_if" name="x_use_kode_if" id="x_use_kode_if" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->use_kode_if->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->use_kode_if->EditValue ?>"<?php echo $z_pay_grp_com->use_kode_if->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->use_kode_if->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->id_kode_if->Visible) { // id_kode_if ?>
	<div id="r_id_kode_if" class="form-group">
		<label id="elh_z_pay_grp_com_id_kode_if" for="x_id_kode_if" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->id_kode_if->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->id_kode_if->CellAttributes() ?>>
<span id="el_z_pay_grp_com_id_kode_if">
<input type="text" data-table="z_pay_grp_com" data-field="x_id_kode_if" name="x_id_kode_if" id="x_id_kode_if" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->id_kode_if->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->id_kode_if->EditValue ?>"<?php echo $z_pay_grp_com->id_kode_if->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->id_kode_if->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->min_if->Visible) { // min_if ?>
	<div id="r_min_if" class="form-group">
		<label id="elh_z_pay_grp_com_min_if" for="x_min_if" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->min_if->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->min_if->CellAttributes() ?>>
<span id="el_z_pay_grp_com_min_if">
<input type="text" data-table="z_pay_grp_com" data-field="x_min_if" name="x_min_if" id="x_min_if" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->min_if->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->min_if->EditValue ?>"<?php echo $z_pay_grp_com->min_if->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->min_if->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->max_if->Visible) { // max_if ?>
	<div id="r_max_if" class="form-group">
		<label id="elh_z_pay_grp_com_max_if" for="x_max_if" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->max_if->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->max_if->CellAttributes() ?>>
<span id="el_z_pay_grp_com_max_if">
<input type="text" data-table="z_pay_grp_com" data-field="x_max_if" name="x_max_if" id="x_max_if" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->max_if->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->max_if->EditValue ?>"<?php echo $z_pay_grp_com->max_if->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->max_if->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->use_sum->Visible) { // use_sum ?>
	<div id="r_use_sum" class="form-group">
		<label id="elh_z_pay_grp_com_use_sum" for="x_use_sum" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->use_sum->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->use_sum->CellAttributes() ?>>
<span id="el_z_pay_grp_com_use_sum">
<input type="text" data-table="z_pay_grp_com" data-field="x_use_sum" name="x_use_sum" id="x_use_sum" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->use_sum->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->use_sum->EditValue ?>"<?php echo $z_pay_grp_com->use_sum->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->use_sum->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->use_kode_sum->Visible) { // use_kode_sum ?>
	<div id="r_use_kode_sum" class="form-group">
		<label id="elh_z_pay_grp_com_use_kode_sum" for="x_use_kode_sum" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->use_kode_sum->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->use_kode_sum->CellAttributes() ?>>
<span id="el_z_pay_grp_com_use_kode_sum">
<input type="text" data-table="z_pay_grp_com" data-field="x_use_kode_sum" name="x_use_kode_sum" id="x_use_kode_sum" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->use_kode_sum->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->use_kode_sum->EditValue ?>"<?php echo $z_pay_grp_com->use_kode_sum->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->use_kode_sum->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->id_kode_sum->Visible) { // id_kode_sum ?>
	<div id="r_id_kode_sum" class="form-group">
		<label id="elh_z_pay_grp_com_id_kode_sum" for="x_id_kode_sum" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->id_kode_sum->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->id_kode_sum->CellAttributes() ?>>
<span id="el_z_pay_grp_com_id_kode_sum">
<input type="text" data-table="z_pay_grp_com" data-field="x_id_kode_sum" name="x_id_kode_sum" id="x_id_kode_sum" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->id_kode_sum->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->id_kode_sum->EditValue ?>"<?php echo $z_pay_grp_com->id_kode_sum->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->id_kode_sum->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->operator_sum->Visible) { // operator_sum ?>
	<div id="r_operator_sum" class="form-group">
		<label id="elh_z_pay_grp_com_operator_sum" for="x_operator_sum" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->operator_sum->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->operator_sum->CellAttributes() ?>>
<span id="el_z_pay_grp_com_operator_sum">
<input type="text" data-table="z_pay_grp_com" data-field="x_operator_sum" name="x_operator_sum" id="x_operator_sum" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->operator_sum->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->operator_sum->EditValue ?>"<?php echo $z_pay_grp_com->operator_sum->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->operator_sum->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->konstanta_sum->Visible) { // konstanta_sum ?>
	<div id="r_konstanta_sum" class="form-group">
		<label id="elh_z_pay_grp_com_konstanta_sum" for="x_konstanta_sum" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->konstanta_sum->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->konstanta_sum->CellAttributes() ?>>
<span id="el_z_pay_grp_com_konstanta_sum">
<input type="text" data-table="z_pay_grp_com" data-field="x_konstanta_sum" name="x_konstanta_sum" id="x_konstanta_sum" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->konstanta_sum->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->konstanta_sum->EditValue ?>"<?php echo $z_pay_grp_com->konstanta_sum->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->konstanta_sum->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->operator_sum2->Visible) { // operator_sum2 ?>
	<div id="r_operator_sum2" class="form-group">
		<label id="elh_z_pay_grp_com_operator_sum2" for="x_operator_sum2" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->operator_sum2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->operator_sum2->CellAttributes() ?>>
<span id="el_z_pay_grp_com_operator_sum2">
<input type="text" data-table="z_pay_grp_com" data-field="x_operator_sum2" name="x_operator_sum2" id="x_operator_sum2" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->operator_sum2->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->operator_sum2->EditValue ?>"<?php echo $z_pay_grp_com->operator_sum2->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->operator_sum2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->nilai_rp->Visible) { // nilai_rp ?>
	<div id="r_nilai_rp" class="form-group">
		<label id="elh_z_pay_grp_com_nilai_rp" for="x_nilai_rp" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->nilai_rp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->nilai_rp->CellAttributes() ?>>
<span id="el_z_pay_grp_com_nilai_rp">
<input type="text" data-table="z_pay_grp_com" data-field="x_nilai_rp" name="x_nilai_rp" id="x_nilai_rp" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->nilai_rp->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->nilai_rp->EditValue ?>"<?php echo $z_pay_grp_com->nilai_rp->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->nilai_rp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->hitung->Visible) { // hitung ?>
	<div id="r_hitung" class="form-group">
		<label id="elh_z_pay_grp_com_hitung" for="x_hitung" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->hitung->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->hitung->CellAttributes() ?>>
<span id="el_z_pay_grp_com_hitung">
<input type="text" data-table="z_pay_grp_com" data-field="x_hitung" name="x_hitung" id="x_hitung" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->hitung->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->hitung->EditValue ?>"<?php echo $z_pay_grp_com->hitung->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->hitung->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp_com->jenis->Visible) { // jenis ?>
	<div id="r_jenis" class="form-group">
		<label id="elh_z_pay_grp_com_jenis" for="x_jenis" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp_com->jenis->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp_com->jenis->CellAttributes() ?>>
<span id="el_z_pay_grp_com_jenis">
<input type="text" data-table="z_pay_grp_com" data-field="x_jenis" name="x_jenis" id="x_jenis" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp_com->jenis->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp_com->jenis->EditValue ?>"<?php echo $z_pay_grp_com->jenis->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp_com->jenis->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$z_pay_grp_com_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $z_pay_grp_com_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fz_pay_grp_comedit.Init();
</script>
<?php
$z_pay_grp_com_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$z_pay_grp_com_edit->Page_Terminate();
?>
