<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "zx_kredit_dinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$zx_kredit_d_delete = NULL; // Initialize page object first

class czx_kredit_d_delete extends czx_kredit_d {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'zx_kredit_d';

	// Page object name
	var $PageObjName = 'zx_kredit_d_delete';

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

		// Table object (zx_kredit_d)
		if (!isset($GLOBALS["zx_kredit_d"]) || get_class($GLOBALS["zx_kredit_d"]) == "czx_kredit_d") {
			$GLOBALS["zx_kredit_d"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["zx_kredit_d"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'zx_kredit_d', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("zx_kredit_dlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_kredit->SetVisibility();
		$this->no_urut->SetVisibility();
		$this->tgl_jt->SetVisibility();
		$this->saldo_aw->SetVisibility();
		$this->debet->SetVisibility();
		$this->angs_pokok->SetVisibility();
		$this->bunga->SetVisibility();
		$this->saldo_akh->SetVisibility();
		$this->proses_bayar->SetVisibility();

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
		global $EW_EXPORT, $zx_kredit_d;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($zx_kredit_d);
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
			$this->Page_Terminate("zx_kredit_dlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in zx_kredit_d class, zx_kredit_dinfo.php

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
				$this->Page_Terminate("zx_kredit_dlist.php"); // Return to list
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
		$this->id_kredit->setDbValue($rs->fields('id_kredit'));
		$this->no_urut->setDbValue($rs->fields('no_urut'));
		$this->tgl_jt->setDbValue($rs->fields('tgl_jt'));
		$this->saldo_aw->setDbValue($rs->fields('saldo_aw'));
		$this->debet->setDbValue($rs->fields('debet'));
		$this->angs_pokok->setDbValue($rs->fields('angs_pokok'));
		$this->bunga->setDbValue($rs->fields('bunga'));
		$this->saldo_akh->setDbValue($rs->fields('saldo_akh'));
		$this->proses_bayar->setDbValue($rs->fields('proses_bayar'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_kredit->DbValue = $row['id_kredit'];
		$this->no_urut->DbValue = $row['no_urut'];
		$this->tgl_jt->DbValue = $row['tgl_jt'];
		$this->saldo_aw->DbValue = $row['saldo_aw'];
		$this->debet->DbValue = $row['debet'];
		$this->angs_pokok->DbValue = $row['angs_pokok'];
		$this->bunga->DbValue = $row['bunga'];
		$this->saldo_akh->DbValue = $row['saldo_akh'];
		$this->proses_bayar->DbValue = $row['proses_bayar'];
		$this->keterangan->DbValue = $row['keterangan'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->saldo_aw->FormValue == $this->saldo_aw->CurrentValue && is_numeric(ew_StrToFloat($this->saldo_aw->CurrentValue)))
			$this->saldo_aw->CurrentValue = ew_StrToFloat($this->saldo_aw->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet->FormValue == $this->debet->CurrentValue && is_numeric(ew_StrToFloat($this->debet->CurrentValue)))
			$this->debet->CurrentValue = ew_StrToFloat($this->debet->CurrentValue);

		// Convert decimal values if posted back
		if ($this->angs_pokok->FormValue == $this->angs_pokok->CurrentValue && is_numeric(ew_StrToFloat($this->angs_pokok->CurrentValue)))
			$this->angs_pokok->CurrentValue = ew_StrToFloat($this->angs_pokok->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bunga->FormValue == $this->bunga->CurrentValue && is_numeric(ew_StrToFloat($this->bunga->CurrentValue)))
			$this->bunga->CurrentValue = ew_StrToFloat($this->bunga->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo_akh->FormValue == $this->saldo_akh->CurrentValue && is_numeric(ew_StrToFloat($this->saldo_akh->CurrentValue)))
			$this->saldo_akh->CurrentValue = ew_StrToFloat($this->saldo_akh->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_kredit
		// no_urut
		// tgl_jt
		// saldo_aw
		// debet
		// angs_pokok
		// bunga
		// saldo_akh
		// proses_bayar
		// keterangan

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

		// saldo_aw
		$this->saldo_aw->ViewValue = $this->saldo_aw->CurrentValue;
		$this->saldo_aw->ViewCustomAttributes = "";

		// debet
		$this->debet->ViewValue = $this->debet->CurrentValue;
		$this->debet->ViewCustomAttributes = "";

		// angs_pokok
		$this->angs_pokok->ViewValue = $this->angs_pokok->CurrentValue;
		$this->angs_pokok->ViewCustomAttributes = "";

		// bunga
		$this->bunga->ViewValue = $this->bunga->CurrentValue;
		$this->bunga->ViewCustomAttributes = "";

		// saldo_akh
		$this->saldo_akh->ViewValue = $this->saldo_akh->CurrentValue;
		$this->saldo_akh->ViewCustomAttributes = "";

		// proses_bayar
		$this->proses_bayar->ViewValue = $this->proses_bayar->CurrentValue;
		$this->proses_bayar->ViewCustomAttributes = "";

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

			// saldo_aw
			$this->saldo_aw->LinkCustomAttributes = "";
			$this->saldo_aw->HrefValue = "";
			$this->saldo_aw->TooltipValue = "";

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

			// saldo_akh
			$this->saldo_akh->LinkCustomAttributes = "";
			$this->saldo_akh->HrefValue = "";
			$this->saldo_akh->TooltipValue = "";

			// proses_bayar
			$this->proses_bayar->LinkCustomAttributes = "";
			$this->proses_bayar->HrefValue = "";
			$this->proses_bayar->TooltipValue = "";
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
				$sThisKey .= $row['id_kredit'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['no_urut'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("zx_kredit_dlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($zx_kredit_d_delete)) $zx_kredit_d_delete = new czx_kredit_d_delete();

// Page init
$zx_kredit_d_delete->Page_Init();

// Page main
$zx_kredit_d_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$zx_kredit_d_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fzx_kredit_ddelete = new ew_Form("fzx_kredit_ddelete", "delete");

// Form_CustomValidate event
fzx_kredit_ddelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fzx_kredit_ddelete.ValidateRequired = true;
<?php } else { ?>
fzx_kredit_ddelete.ValidateRequired = false; 
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
<?php $zx_kredit_d_delete->ShowPageHeader(); ?>
<?php
$zx_kredit_d_delete->ShowMessage();
?>
<form name="fzx_kredit_ddelete" id="fzx_kredit_ddelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($zx_kredit_d_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $zx_kredit_d_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="zx_kredit_d">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($zx_kredit_d_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $zx_kredit_d->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($zx_kredit_d->id_kredit->Visible) { // id_kredit ?>
		<th><span id="elh_zx_kredit_d_id_kredit" class="zx_kredit_d_id_kredit"><?php echo $zx_kredit_d->id_kredit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_d->no_urut->Visible) { // no_urut ?>
		<th><span id="elh_zx_kredit_d_no_urut" class="zx_kredit_d_no_urut"><?php echo $zx_kredit_d->no_urut->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_d->tgl_jt->Visible) { // tgl_jt ?>
		<th><span id="elh_zx_kredit_d_tgl_jt" class="zx_kredit_d_tgl_jt"><?php echo $zx_kredit_d->tgl_jt->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_d->saldo_aw->Visible) { // saldo_aw ?>
		<th><span id="elh_zx_kredit_d_saldo_aw" class="zx_kredit_d_saldo_aw"><?php echo $zx_kredit_d->saldo_aw->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_d->debet->Visible) { // debet ?>
		<th><span id="elh_zx_kredit_d_debet" class="zx_kredit_d_debet"><?php echo $zx_kredit_d->debet->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_d->angs_pokok->Visible) { // angs_pokok ?>
		<th><span id="elh_zx_kredit_d_angs_pokok" class="zx_kredit_d_angs_pokok"><?php echo $zx_kredit_d->angs_pokok->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_d->bunga->Visible) { // bunga ?>
		<th><span id="elh_zx_kredit_d_bunga" class="zx_kredit_d_bunga"><?php echo $zx_kredit_d->bunga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_d->saldo_akh->Visible) { // saldo_akh ?>
		<th><span id="elh_zx_kredit_d_saldo_akh" class="zx_kredit_d_saldo_akh"><?php echo $zx_kredit_d->saldo_akh->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_d->proses_bayar->Visible) { // proses_bayar ?>
		<th><span id="elh_zx_kredit_d_proses_bayar" class="zx_kredit_d_proses_bayar"><?php echo $zx_kredit_d->proses_bayar->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$zx_kredit_d_delete->RecCnt = 0;
$i = 0;
while (!$zx_kredit_d_delete->Recordset->EOF) {
	$zx_kredit_d_delete->RecCnt++;
	$zx_kredit_d_delete->RowCnt++;

	// Set row properties
	$zx_kredit_d->ResetAttrs();
	$zx_kredit_d->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$zx_kredit_d_delete->LoadRowValues($zx_kredit_d_delete->Recordset);

	// Render row
	$zx_kredit_d_delete->RenderRow();
?>
	<tr<?php echo $zx_kredit_d->RowAttributes() ?>>
<?php if ($zx_kredit_d->id_kredit->Visible) { // id_kredit ?>
		<td<?php echo $zx_kredit_d->id_kredit->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_d_delete->RowCnt ?>_zx_kredit_d_id_kredit" class="zx_kredit_d_id_kredit">
<span<?php echo $zx_kredit_d->id_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_d->id_kredit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_d->no_urut->Visible) { // no_urut ?>
		<td<?php echo $zx_kredit_d->no_urut->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_d_delete->RowCnt ?>_zx_kredit_d_no_urut" class="zx_kredit_d_no_urut">
<span<?php echo $zx_kredit_d->no_urut->ViewAttributes() ?>>
<?php echo $zx_kredit_d->no_urut->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_d->tgl_jt->Visible) { // tgl_jt ?>
		<td<?php echo $zx_kredit_d->tgl_jt->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_d_delete->RowCnt ?>_zx_kredit_d_tgl_jt" class="zx_kredit_d_tgl_jt">
<span<?php echo $zx_kredit_d->tgl_jt->ViewAttributes() ?>>
<?php echo $zx_kredit_d->tgl_jt->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_d->saldo_aw->Visible) { // saldo_aw ?>
		<td<?php echo $zx_kredit_d->saldo_aw->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_d_delete->RowCnt ?>_zx_kredit_d_saldo_aw" class="zx_kredit_d_saldo_aw">
<span<?php echo $zx_kredit_d->saldo_aw->ViewAttributes() ?>>
<?php echo $zx_kredit_d->saldo_aw->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_d->debet->Visible) { // debet ?>
		<td<?php echo $zx_kredit_d->debet->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_d_delete->RowCnt ?>_zx_kredit_d_debet" class="zx_kredit_d_debet">
<span<?php echo $zx_kredit_d->debet->ViewAttributes() ?>>
<?php echo $zx_kredit_d->debet->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_d->angs_pokok->Visible) { // angs_pokok ?>
		<td<?php echo $zx_kredit_d->angs_pokok->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_d_delete->RowCnt ?>_zx_kredit_d_angs_pokok" class="zx_kredit_d_angs_pokok">
<span<?php echo $zx_kredit_d->angs_pokok->ViewAttributes() ?>>
<?php echo $zx_kredit_d->angs_pokok->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_d->bunga->Visible) { // bunga ?>
		<td<?php echo $zx_kredit_d->bunga->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_d_delete->RowCnt ?>_zx_kredit_d_bunga" class="zx_kredit_d_bunga">
<span<?php echo $zx_kredit_d->bunga->ViewAttributes() ?>>
<?php echo $zx_kredit_d->bunga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_d->saldo_akh->Visible) { // saldo_akh ?>
		<td<?php echo $zx_kredit_d->saldo_akh->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_d_delete->RowCnt ?>_zx_kredit_d_saldo_akh" class="zx_kredit_d_saldo_akh">
<span<?php echo $zx_kredit_d->saldo_akh->ViewAttributes() ?>>
<?php echo $zx_kredit_d->saldo_akh->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_d->proses_bayar->Visible) { // proses_bayar ?>
		<td<?php echo $zx_kredit_d->proses_bayar->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_d_delete->RowCnt ?>_zx_kredit_d_proses_bayar" class="zx_kredit_d_proses_bayar">
<span<?php echo $zx_kredit_d->proses_bayar->ViewAttributes() ?>>
<?php echo $zx_kredit_d->proses_bayar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$zx_kredit_d_delete->Recordset->MoveNext();
}
$zx_kredit_d_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $zx_kredit_d_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fzx_kredit_ddelete.Init();
</script>
<?php
$zx_kredit_d_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$zx_kredit_d_delete->Page_Terminate();
?>
