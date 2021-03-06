<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "ganti_jdw_jkinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$ganti_jdw_jk_delete = NULL; // Initialize page object first

class cganti_jdw_jk_delete extends cganti_jdw_jk {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'ganti_jdw_jk';

	// Page object name
	var $PageObjName = 'ganti_jdw_jk_delete';

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

		// Table object (ganti_jdw_jk)
		if (!isset($GLOBALS["ganti_jdw_jk"]) || get_class($GLOBALS["ganti_jdw_jk"]) == "cganti_jdw_jk") {
			$GLOBALS["ganti_jdw_jk"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ganti_jdw_jk"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ganti_jdw_jk', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("ganti_jdw_jklist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->ganti_jdw_id->SetVisibility();
		$this->jdw_kerja_m_id1->SetVisibility();
		$this->jdw_kerja_m_id2->SetVisibility();
		$this->tgl_awal->SetVisibility();
		$this->tgl_akhir->SetVisibility();
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
		global $EW_EXPORT, $ganti_jdw_jk;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($ganti_jdw_jk);
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("ganti_jdw_jklist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in ganti_jdw_jk class, ganti_jdw_jkinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("ganti_jdw_jklist.php"); // Return to list
			}
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
		$this->ganti_jdw_id->setDbValue($rs->fields('ganti_jdw_id'));
		$this->jdw_kerja_m_id1->setDbValue($rs->fields('jdw_kerja_m_id1'));
		$this->jdw_kerja_m_id2->setDbValue($rs->fields('jdw_kerja_m_id2'));
		$this->tgl_awal->setDbValue($rs->fields('tgl_awal'));
		$this->tgl_akhir->setDbValue($rs->fields('tgl_akhir'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ganti_jdw_id->DbValue = $row['ganti_jdw_id'];
		$this->jdw_kerja_m_id1->DbValue = $row['jdw_kerja_m_id1'];
		$this->jdw_kerja_m_id2->DbValue = $row['jdw_kerja_m_id2'];
		$this->tgl_awal->DbValue = $row['tgl_awal'];
		$this->tgl_akhir->DbValue = $row['tgl_akhir'];
		$this->keterangan->DbValue = $row['keterangan'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// ganti_jdw_id
		// jdw_kerja_m_id1
		// jdw_kerja_m_id2
		// tgl_awal
		// tgl_akhir
		// keterangan

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ganti_jdw_id
		$this->ganti_jdw_id->ViewValue = $this->ganti_jdw_id->CurrentValue;
		$this->ganti_jdw_id->ViewCustomAttributes = "";

		// jdw_kerja_m_id1
		$this->jdw_kerja_m_id1->ViewValue = $this->jdw_kerja_m_id1->CurrentValue;
		$this->jdw_kerja_m_id1->ViewCustomAttributes = "";

		// jdw_kerja_m_id2
		$this->jdw_kerja_m_id2->ViewValue = $this->jdw_kerja_m_id2->CurrentValue;
		$this->jdw_kerja_m_id2->ViewCustomAttributes = "";

		// tgl_awal
		$this->tgl_awal->ViewValue = $this->tgl_awal->CurrentValue;
		$this->tgl_awal->ViewValue = ew_FormatDateTime($this->tgl_awal->ViewValue, 0);
		$this->tgl_awal->ViewCustomAttributes = "";

		// tgl_akhir
		$this->tgl_akhir->ViewValue = $this->tgl_akhir->CurrentValue;
		$this->tgl_akhir->ViewValue = ew_FormatDateTime($this->tgl_akhir->ViewValue, 0);
		$this->tgl_akhir->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

			// ganti_jdw_id
			$this->ganti_jdw_id->LinkCustomAttributes = "";
			$this->ganti_jdw_id->HrefValue = "";
			$this->ganti_jdw_id->TooltipValue = "";

			// jdw_kerja_m_id1
			$this->jdw_kerja_m_id1->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id1->HrefValue = "";
			$this->jdw_kerja_m_id1->TooltipValue = "";

			// jdw_kerja_m_id2
			$this->jdw_kerja_m_id2->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id2->HrefValue = "";
			$this->jdw_kerja_m_id2->TooltipValue = "";

			// tgl_awal
			$this->tgl_awal->LinkCustomAttributes = "";
			$this->tgl_awal->HrefValue = "";
			$this->tgl_awal->TooltipValue = "";

			// tgl_akhir
			$this->tgl_akhir->LinkCustomAttributes = "";
			$this->tgl_akhir->HrefValue = "";
			$this->tgl_akhir->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['ganti_jdw_id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("ganti_jdw_jklist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($ganti_jdw_jk_delete)) $ganti_jdw_jk_delete = new cganti_jdw_jk_delete();

// Page init
$ganti_jdw_jk_delete->Page_Init();

// Page main
$ganti_jdw_jk_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ganti_jdw_jk_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fganti_jdw_jkdelete = new ew_Form("fganti_jdw_jkdelete", "delete");

// Form_CustomValidate event
fganti_jdw_jkdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fganti_jdw_jkdelete.ValidateRequired = true;
<?php } else { ?>
fganti_jdw_jkdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $ganti_jdw_jk_delete->ShowPageHeader(); ?>
<?php
$ganti_jdw_jk_delete->ShowMessage();
?>
<form name="fganti_jdw_jkdelete" id="fganti_jdw_jkdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($ganti_jdw_jk_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $ganti_jdw_jk_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="ganti_jdw_jk">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($ganti_jdw_jk_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $ganti_jdw_jk->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($ganti_jdw_jk->ganti_jdw_id->Visible) { // ganti_jdw_id ?>
		<th><span id="elh_ganti_jdw_jk_ganti_jdw_id" class="ganti_jdw_jk_ganti_jdw_id"><?php echo $ganti_jdw_jk->ganti_jdw_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($ganti_jdw_jk->jdw_kerja_m_id1->Visible) { // jdw_kerja_m_id1 ?>
		<th><span id="elh_ganti_jdw_jk_jdw_kerja_m_id1" class="ganti_jdw_jk_jdw_kerja_m_id1"><?php echo $ganti_jdw_jk->jdw_kerja_m_id1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($ganti_jdw_jk->jdw_kerja_m_id2->Visible) { // jdw_kerja_m_id2 ?>
		<th><span id="elh_ganti_jdw_jk_jdw_kerja_m_id2" class="ganti_jdw_jk_jdw_kerja_m_id2"><?php echo $ganti_jdw_jk->jdw_kerja_m_id2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($ganti_jdw_jk->tgl_awal->Visible) { // tgl_awal ?>
		<th><span id="elh_ganti_jdw_jk_tgl_awal" class="ganti_jdw_jk_tgl_awal"><?php echo $ganti_jdw_jk->tgl_awal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($ganti_jdw_jk->tgl_akhir->Visible) { // tgl_akhir ?>
		<th><span id="elh_ganti_jdw_jk_tgl_akhir" class="ganti_jdw_jk_tgl_akhir"><?php echo $ganti_jdw_jk->tgl_akhir->FldCaption() ?></span></th>
<?php } ?>
<?php if ($ganti_jdw_jk->keterangan->Visible) { // keterangan ?>
		<th><span id="elh_ganti_jdw_jk_keterangan" class="ganti_jdw_jk_keterangan"><?php echo $ganti_jdw_jk->keterangan->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$ganti_jdw_jk_delete->RecCnt = 0;
$i = 0;
while (!$ganti_jdw_jk_delete->Recordset->EOF) {
	$ganti_jdw_jk_delete->RecCnt++;
	$ganti_jdw_jk_delete->RowCnt++;

	// Set row properties
	$ganti_jdw_jk->ResetAttrs();
	$ganti_jdw_jk->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$ganti_jdw_jk_delete->LoadRowValues($ganti_jdw_jk_delete->Recordset);

	// Render row
	$ganti_jdw_jk_delete->RenderRow();
?>
	<tr<?php echo $ganti_jdw_jk->RowAttributes() ?>>
<?php if ($ganti_jdw_jk->ganti_jdw_id->Visible) { // ganti_jdw_id ?>
		<td<?php echo $ganti_jdw_jk->ganti_jdw_id->CellAttributes() ?>>
<span id="el<?php echo $ganti_jdw_jk_delete->RowCnt ?>_ganti_jdw_jk_ganti_jdw_id" class="ganti_jdw_jk_ganti_jdw_id">
<span<?php echo $ganti_jdw_jk->ganti_jdw_id->ViewAttributes() ?>>
<?php echo $ganti_jdw_jk->ganti_jdw_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($ganti_jdw_jk->jdw_kerja_m_id1->Visible) { // jdw_kerja_m_id1 ?>
		<td<?php echo $ganti_jdw_jk->jdw_kerja_m_id1->CellAttributes() ?>>
<span id="el<?php echo $ganti_jdw_jk_delete->RowCnt ?>_ganti_jdw_jk_jdw_kerja_m_id1" class="ganti_jdw_jk_jdw_kerja_m_id1">
<span<?php echo $ganti_jdw_jk->jdw_kerja_m_id1->ViewAttributes() ?>>
<?php echo $ganti_jdw_jk->jdw_kerja_m_id1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($ganti_jdw_jk->jdw_kerja_m_id2->Visible) { // jdw_kerja_m_id2 ?>
		<td<?php echo $ganti_jdw_jk->jdw_kerja_m_id2->CellAttributes() ?>>
<span id="el<?php echo $ganti_jdw_jk_delete->RowCnt ?>_ganti_jdw_jk_jdw_kerja_m_id2" class="ganti_jdw_jk_jdw_kerja_m_id2">
<span<?php echo $ganti_jdw_jk->jdw_kerja_m_id2->ViewAttributes() ?>>
<?php echo $ganti_jdw_jk->jdw_kerja_m_id2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($ganti_jdw_jk->tgl_awal->Visible) { // tgl_awal ?>
		<td<?php echo $ganti_jdw_jk->tgl_awal->CellAttributes() ?>>
<span id="el<?php echo $ganti_jdw_jk_delete->RowCnt ?>_ganti_jdw_jk_tgl_awal" class="ganti_jdw_jk_tgl_awal">
<span<?php echo $ganti_jdw_jk->tgl_awal->ViewAttributes() ?>>
<?php echo $ganti_jdw_jk->tgl_awal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($ganti_jdw_jk->tgl_akhir->Visible) { // tgl_akhir ?>
		<td<?php echo $ganti_jdw_jk->tgl_akhir->CellAttributes() ?>>
<span id="el<?php echo $ganti_jdw_jk_delete->RowCnt ?>_ganti_jdw_jk_tgl_akhir" class="ganti_jdw_jk_tgl_akhir">
<span<?php echo $ganti_jdw_jk->tgl_akhir->ViewAttributes() ?>>
<?php echo $ganti_jdw_jk->tgl_akhir->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($ganti_jdw_jk->keterangan->Visible) { // keterangan ?>
		<td<?php echo $ganti_jdw_jk->keterangan->CellAttributes() ?>>
<span id="el<?php echo $ganti_jdw_jk_delete->RowCnt ?>_ganti_jdw_jk_keterangan" class="ganti_jdw_jk_keterangan">
<span<?php echo $ganti_jdw_jk->keterangan->ViewAttributes() ?>>
<?php echo $ganti_jdw_jk->keterangan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$ganti_jdw_jk_delete->Recordset->MoveNext();
}
$ganti_jdw_jk_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $ganti_jdw_jk_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fganti_jdw_jkdelete.Init();
</script>
<?php
$ganti_jdw_jk_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ganti_jdw_jk_delete->Page_Terminate();
?>
