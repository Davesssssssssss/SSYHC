<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purple HRIS & Payroll</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        /* --- ORIGINAL STYLES --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f7; display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: white; padding: 30px 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.05); overflow-y: auto; height: 100vh; position: fixed;}
        .logo { display: flex; align-items: center; gap: 10px; margin-bottom: 40px; font-size: 24px; font-weight: bold; color: #a78bfa; }
        .logo-icon { width: 30px; height: 30px; background: #a78bfa; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; }
        .user-profile { display: flex; align-items: center; gap: 12px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #f0f0f0; }
        .user-avatar { width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
        .user-info h4 { font-size: 14px; color: #1a1a1a; margin-bottom: 4px; }
        .user-info p { font-size: 12px; color: #999; }
        .nav-item { padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; cursor: pointer; transition: all 0.3s; font-size: 14px; color: #666; }
        .nav-item:hover { background: #f5f5f5; color: #a78bfa; }
        .nav-item.active { background: #f0e6ff; color: #a78bfa; font-weight: 600; }
        
        .main-content { flex: 1; padding: 40px; margin-left: 260px; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; color: #1a1a1a; display: flex; align-items: center; gap: 12px; }
        .header-icon { width: 40px; height: 40px; background: #a78bfa; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; }
        
        /* Dashboard Cards */
        .top-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .card { padding: 30px; border-radius: 16px; color: white; position: relative; overflow: hidden; }
        .card::before { content: ''; position: absolute; top: -50%; right: -50%; width: 300px; height: 300px; border-radius: 50%; opacity: 0.2; }
        .card.pink { background: linear-gradient(135deg, #ff9a56 0%, #ff6b9d 100%); }
        .card.blue { background: linear-gradient(135deg, #667eea 0%, #5a9fd4 100%); }
        .card.green { background: linear-gradient(135deg, #13d0ce 0%, #29ffc6 100%); }
        .card-icon { font-size: 28px; margin-bottom: 15px; opacity: 0.8; }
        .card-title { font-size: 14px; opacity: 0.9; margin-bottom: 10px; }
        .card-value { font-size: 32px; font-weight: bold; margin-bottom: 15px; }
        
        /* Charts */
        .bottom-section { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
        .chart-card { background: white; padding: 25px; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .chart-container { position: relative; height: 300px; width: 100%; }
        .pie-container { display: flex; justify-content: center; height: 250px; }

        /* --- NEW FUNCTIONAL STYLES --- */
        .tab-content { display: none; animation: fadeIn 0.3s ease; }
        .tab-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* Forms and Tables */
        .panel { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 20px; }
        .panel h3 { margin-bottom: 20px; color: #444; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group label { font-size: 13px; font-weight: 600; color: #666; }
        .form-group input, .form-group select { padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .form-group input:focus { outline: none; border-color: #a78bfa; }

        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: 0.2s; }
        .btn-primary { background: #a78bfa; color: white; }
        .btn-primary:hover { background: #8b5cf6; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-success { background: #10b981; color: white; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
        th { color: #666; font-weight: 600; background: #f9f9f9; }
        td { color: #333; }
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; }
        .status-active { background: #d1fae5; color: #065f46; }
        .status-inactive { background: #fee2e2; color: #991b1b; }
        .status-paid { background: #dbeafe; color: #1e40af; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-open { background: #f3f4f6; color: #374151; }

        /* Payslip Modal */
        .payslip-view { border: 1px solid #ccc; padding: 40px; background: #fff; max-width: 600px; margin: 0 auto; font-family: 'Courier New', Courier, monospace; }
        .payslip-header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 20px; margin-bottom: 20px; }
        .payslip-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .payslip-total { border-top: 2px dashed #000; padding-top: 10px; font-weight: bold; margin-top: 10px; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; padding: 20px; }
            .top-cards, .bottom-section { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <div class="logo-icon">◇</div>
            <span>Purple HR</span>
        </div>
        <div class="user-profile">
            <div class="user-avatar">AD</div>
            <div class="user-info">
                <h4>Admin User</h4>
                <p>HR Manager</p>
            </div>
        </div>

        <div class="nav-item active" onclick="switchTab('dashboard')">📊 Dashboard</div>
        <div class="nav-item" onclick="switchTab('employee')">📄 Employee Mgmt</div>
        <div class="nav-item" onclick="switchTab('timekeeping')">⏱️ Timekeeping</div>
        <div class="nav-item" onclick="switchTab('payroll-period')">📅 Payroll Periods</div>
        <div class="nav-item" onclick="switchTab('payroll-computation')">📐 Computation</div>
        <div class="nav-item" onclick="switchTab('payroll-validation')">✅ Validation</div>
        <div class="nav-item" onclick="switchTab('salary-release')">💸 Salary Release</div>
        <div class="nav-item" onclick="switchTab('payslip')">🧾 Payslips</div>
        <div class="nav-item" onclick="switchTab('reports')">📈 Reports</div>
        <div class="nav-item" onclick="switchTab('admin')">⚙️ Admin</div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1><div class="header-icon">◇</div> <span id="pageTitle">Dashboard</span></h1>
        </div>

        <!-- 1. DASHBOARD -->
        <div id="dashboard" class="tab-content active">
            <div class="top-cards">
                <div class="card pink">
                    <div class="card-icon">👥</div>
                    <div class="card-title">Total Employees</div>
                    <div class="card-value" id="dash-total-emp">0</div>
                    <div class="card-change">Active Staff</div>
                </div>
                <div class="card blue">
                    <div class="card-icon">💰</div>
                    <div class="card-title">Total Payroll (YTD)</div>
                    <div class="card-value" id="dash-total-pay">$0</div>
                    <div class="card-change">Paid Out</div>
                </div>
                <div class="card green">
                    <div class="card-icon">✅</div>
                    <div class="card-title">Last Payroll Status</div>
                    <div class="card-value">Done</div>
                    <div class="card-change">On Time</div>
                </div>
            </div>
            <div class="bottom-section">
                <div class="chart-card">
                    <h3>Monthly Expenses</h3>
                    <div class="chart-container"><canvas id="barChart"></canvas></div>
                </div>
                <div class="chart-card">
                    <h3>Employee Status</h3>
                    <div class="pie-container"><canvas id="pieChart"></canvas></div>
                </div>
            </div>
        </div>

        <!-- 2. EMPLOYEE MANAGEMENT -->
        <div id="employee" class="tab-content">
            <div class="panel">
                <h3>Add New Employee</h3>
                <form id="empForm" onsubmit="addEmployee(event)">
                    <div class="form-grid">
                        <div class="form-group"><label>Full Name</label><input type="text" id="empName" required placeholder="e.g. John Doe"></div>
                        <div class="form-group"><label>Position</label><input type="text" id="empPos" required placeholder="e.g. Developer"></div>
                        <div class="form-group"><label>Department</label><select id="empDept"><option>IT</option><option>HR</option><option>Sales</option><option>Operations</option></select></div>
                        <div class="form-group"><label>Basic Monthly Salary</label><input type="number" id="empSalary" required></div>
                        <div class="form-group"><label>Status</label><select id="empStatus"><option value="Active">Active</option><option value="Resigned">Resigned</option></select></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Employee</button>
                </form>
            </div>
            <div class="panel">
                <h3>Employee List</h3>
                <table>
                    <thead><tr><th>ID</th><th>Name</th><th>Position</th><th>Dept</th><th>Salary</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody id="employeeTable"></tbody>
                </table>
            </div>
        </div>

        <!-- 3. TIMEKEEPING -->
        <div id="timekeeping" class="tab-content">
            <div class="panel">
                <h3>Daily Attendance</h3>
                <div class="form-grid">
                    <div class="form-group"><label>Select Employee</label><select id="timeEmployee"></select></div>
                    <div class="form-group"><label>Date</label><input type="date" id="timeDate"></div>
                    <div class="form-group"><label>Time In</label><input type="time" id="timeIn"></div>
                    <div class="form-group"><label>Time Out</label><input type="time" id="timeOut"></div>
                </div>
                <button onclick="logTime()" class="btn btn-primary">Log Attendance</button>
            </div>
            <div class="panel">
                <h3>Recent Logs</h3>
                <table>
                    <thead><tr><th>Date</th><th>Employee</th><th>In</th><th>Out</th><th>Hours</th><th>Status</th></tr></thead>
                    <tbody id="attendanceTable"></tbody>
                </table>
            </div>
        </div>

        <!-- 4. PAYROLL PERIODS -->
        <div id="payroll-period" class="tab-content">
            <div class="panel">
                <h3>Create Payroll Period</h3>
                <div class="form-grid">
                    <div class="form-group"><label>Start Date</label><input type="date" id="pStart"></div>
                    <div class="form-group"><label>End Date</label><input type="date" id="pEnd"></div>
                    <div class="form-group"><label>Payout Date</label><input type="date" id="pPayDate"></div>
                </div>
                <button onclick="createPeriod()" class="btn btn-primary">Create Period</button>
            </div>
            <div class="panel">
                <h3>Periods History</h3>
                <table>
                    <thead><tr><th>Period ID</th><th>Range</th><th>Pay Date</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody id="periodTable"></tbody>
                </table>
            </div>
        </div>

        <!-- 5. COMPUTATION -->
        <div id="payroll-computation" class="tab-content">
            <div class="panel">
                <h3>Run Payroll Computation</h3>
                <p>Select a period to calculate salaries based on attendance and basic rates.</p>
                <div class="form-grid">
                    <div class="form-group"><label>Select Open Period</label><select id="compPeriod"></select></div>
                </div>
                <button onclick="runComputation()" class="btn btn-primary">Compute & Preview</button>
            </div>
            <div id="computationResult" class="panel" style="display:none;">
                <h3>Computation Preview</h3>
                <table>
                    <thead><tr><th>Emp</th><th>Gross</th><th>Tax (10%)</th><th>SSS/Phil/Pag</th><th>Net Pay</th></tr></thead>
                    <tbody id="compTable"></tbody>
                </table>
                <br>
                <button onclick="submitForValidation()" class="btn btn-success">Submit for Validation</button>
            </div>
        </div>

        <!-- 6. VALIDATION -->
        <div id="payroll-validation" class="tab-content">
            <div class="panel">
                <h3>Pending Approvals</h3>
                <table id="validationTable">
                    <!-- Populated via JS -->
                </table>
            </div>
        </div>

        <!-- 7. SALARY RELEASE -->
        <div id="salary-release" class="tab-content">
            <div class="panel">
                <h3>Disbursement</h3>
                <div class="form-grid">
                    <div class="form-group"><label>Select Approved Payroll</label><select id="releasePeriod"></select></div>
                    <div class="form-group"><label>Payment Method</label><select id="payMethod"><option>Bank Transfer</option><option>Cash</option><option>Check</option></select></div>
                </div>
                <button onclick="releaseSalary()" class="btn btn-primary">Release Salaries</button>
            </div>
            <div class="panel">
                <h3>Release History</h3>
                <ul id="releaseLog">
                    <!-- Populated via JS -->
                </ul>
            </div>
        </div>

        <!-- 8. PAYSLIPS -->
        <div id="payslip" class="tab-content">
            <div class="panel">
                <h3>Generate Payslip</h3>
                <div class="form-grid">
                    <div class="form-group"><label>Employee</label><select id="psEmployee"></select></div>
                    <div class="form-group"><label>Period</label><select id="psPeriod"></select></div>
                </div>
                <button onclick="generatePayslip()" class="btn btn-primary">View Payslip</button>
            </div>
            <div id="payslipView" style="display:none; margin-top:20px;">
                <div class="payslip-view">
                    <div class="payslip-header">
                        <h2>PURPLE INC.</h2>
                        <p>Payslip</p>
                    </div>
                    <div id="psContent"></div>
                </div>
                <div style="text-align:center; margin-top:10px;">
                    <button class="btn btn-primary" onclick="window.print()">Print / Save as PDF</button>
                </div>
            </div>
        </div>

        <!-- 9. REPORTS -->
        <div id="reports" class="tab-content">
            <div class="panel">
                <h3>Financial Summary</h3>
                <div class="form-grid">
                    <div class="card blue"><div class="card-title">Total Taxes Remitted</div><div class="card-value" id="rep-tax">$0</div></div>
                    <div class="card pink"><div class="card-title">Total Deductions</div><div class="card-value" id="rep-deduct">$0</div></div>
                </div>
            </div>
        </div>

        <!-- 10. ADMIN -->
        <div id="admin" class="tab-content">
            <div class="panel">
                <h3>System Administration</h3>
                <div class="form-group">
                    <label>Data Management</label>
                    <button onclick="clearData()" class="btn btn-danger">Reset System Data (Clear LocalStorage)</button>
                    <p style="margin-top:5px; font-size:12px; color:#666;">Warning: This will delete all employees, logs, and payrolls.</p>
                </div>
            </div>
            <div class="panel">
                <h3>Audit Logs</h3>
                <ul id="auditLog" style="list-style:none; font-size:13px; color:#555;"></ul>
            </div>
        </div>
    </div>

    <script>
        // --- DATA STORE INITIALIZATION ---
        const db = {
            employees: JSON.parse(localStorage.getItem('purple_employees')) || [],
            attendance: JSON.parse(localStorage.getItem('purple_attendance')) || [],
            periods: JSON.parse(localStorage.getItem('purple_periods')) || [],
            payrolls: JSON.parse(localStorage.getItem('purple_payrolls')) || [], // Computed items
            audit: JSON.parse(localStorage.getItem('purple_audit')) || []
        };

        // --- SAMPLE DATA INJECTION ---
        function initSampleData() {
            if (db.employees.length === 0) {
                console.log("Initializing Sample Data...");

                // 1. Employees
                const emps = [
                    { id: 101, name: "David Grey", position: "Project Manager", dept: "IT", salary: 75000, status: "Active" },
                    { id: 102, name: "Sarah Connor", position: "HR Officer", dept: "HR", salary: 45000, status: "Active" },
                    { id: 103, name: "John Wick", position: "Security Head", dept: "Operations", salary: 35000, status: "Active" },
                    { id: 104, name: "Emily Blunt", position: "Sales Exec", dept: "Sales", salary: 40000, status: "Active" },
                    { id: 105, name: "Tony Stark", position: "Consultant", dept: "IT", salary: 120000, status: "Resigned" }
                ];
                db.employees = emps;

                // 2. Periods
                const periods = [
                    // Paid Period
                    { id: 20231015, range: "2023-10-01 to 2023-10-15", payDate: "2023-10-15", status: "Paid" },
                    // Approved Period (Ready for release)
                    { id: 20231030, range: "2023-10-16 to 2023-10-31", payDate: "2023-10-30", status: "Approved" },
                    // Open Period (For computation)
                    { id: 20231115, range: "2023-11-01 to 2023-11-15", payDate: "2023-11-15", status: "Open" }
                ];
                db.periods = periods;

                // 3. Computed Payrolls (Only for the Paid and Approved periods)
                // Helper to create dummy payroll
                const createPay = (pId, emp) => {
                    const gross = emp.salary / 2; // Half month
                    const tax = gross * 0.10;
                    const sss = gross * 0.045;
                    const phil = gross * 0.04;
                    const pagibig = 100;
                    const totalDed = tax + sss + phil + pagibig;
                    return {
                        periodId: pId,
                        empId: emp.id,
                        empName: emp.name,
                        gross: gross,
                        deductions: { tax, sss, phil, pagibig },
                        totalDed: totalDed,
                        net: gross - totalDed
                    };
                };

                // Add payrolls for Paid period
                emps.filter(e => e.status === 'Active').forEach(e => {
                    db.payrolls.push(createPay(20231015, e));
                    db.payrolls.push(createPay(20231030, e)); // For the Approved period
                });

                // 4. Attendance (Logs for the Open period)
                const logs = [
                    { id: 1, empId: 101, empName: "David Grey", date: "2023-11-01", tIn: "08:00", tOut: "17:00", hours: "9.00" },
                    { id: 2, empId: 102, empName: "Sarah Connor", date: "2023-11-01", tIn: "08:15", tOut: "17:15", hours: "9.00" },
                    { id: 3, empId: 103, empName: "John Wick", date: "2023-11-01", tIn: "07:30", tOut: "16:30", hours: "9.00" },
                    { id: 4, empId: 101, empName: "David Grey", date: "2023-11-02", tIn: "08:00", tOut: "17:00", hours: "9.00" }
                ];
                db.attendance = logs;

                // 5. Audit
                db.audit = [
                    "10/15/2023, 5:00:00 PM - Released Salaries for Period 20231015",
                    "10/30/2023, 2:00:00 PM - Approved Payroll for Period 20231030",
                    "11/01/2023, 8:00:00 AM - System Backup Completed"
                ];

                saveData();
            }
        }

        function saveData() {
            localStorage.setItem('purple_employees', JSON.stringify(db.employees));
            localStorage.setItem('purple_attendance', JSON.stringify(db.attendance));
            localStorage.setItem('purple_periods', JSON.stringify(db.periods));
            localStorage.setItem('purple_payrolls', JSON.stringify(db.payrolls));
            localStorage.setItem('purple_audit', JSON.stringify(db.audit));
            updateDashboard();
        }

        function logAction(action) {
            const entry = `${new Date().toLocaleString()} - ${action}`;
            db.audit.unshift(entry);
            saveData();
            renderAudit();
        }

        // --- TAB NAVIGATION ---
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            
            // Sidebar active state
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            event.currentTarget.classList.add('active');

            // Update Header
            document.getElementById('pageTitle').innerText = tabId.replace('-', ' ').toUpperCase();

            // Refresh specific tab data
            if(tabId === 'employee') renderEmployees();
            if(tabId === 'timekeeping') { renderTimeOptions(); renderAttendance(); }
            if(tabId === 'payroll-period') renderPeriods();
            if(tabId === 'payroll-computation') renderCompOptions();
            if(tabId === 'payroll-validation') renderValidation();
            if(tabId === 'salary-release') renderReleaseOptions();
            if(tabId === 'payslip') renderPayslipOptions();
            if(tabId === 'dashboard') updateDashboard();
            if(tabId === 'admin') renderAudit();
        }

        // --- 1. EMPLOYEE MANAGEMENT ---
        function addEmployee(e) {
            e.preventDefault();
            const newEmp = {
                id: Date.now(),
                name: document.getElementById('empName').value,
                position: document.getElementById('empPos').value,
                dept: document.getElementById('empDept').value,
                salary: parseFloat(document.getElementById('empSalary').value),
                status: document.getElementById('empStatus').value
            };
            db.employees.push(newEmp);
            saveData();
            e.target.reset();
            renderEmployees();
            logAction(`Added employee: ${newEmp.name}`);
            alert('Employee Added!');
        }

        function renderEmployees() {
            const tbody = document.getElementById('employeeTable');
            tbody.innerHTML = db.employees.map(e => `
                <tr>
                    <td>${e.id}</td>
                    <td>${e.name}</td>
                    <td>${e.position}</td>
                    <td>${e.dept}</td>
                    <td>$${e.salary.toLocaleString()}</td>
                    <td><span class="status-badge ${e.status === 'Active' ? 'status-active' : 'status-inactive'}">${e.status}</span></td>
                    <td><button class="btn btn-danger" style="padding:2px 8px; font-size:12px;" onclick="deleteEmp(${e.id})">Delete</button></td>
                </tr>
            `).join('');
            document.getElementById('dash-total-emp').innerText = db.employees.length;
        }

        function deleteEmp(id) {
            if(confirm('Delete this employee?')) {
                db.employees = db.employees.filter(e => e.id !== id);
                saveData();
                renderEmployees();
            }
        }

        // --- 2. TIMEKEEPING ---
        function renderTimeOptions() {
            const sel = document.getElementById('timeEmployee');
            sel.innerHTML = db.employees.map(e => `<option value="${e.id}">${e.name}</option>`).join('');
            document.getElementById('timeDate').valueAsDate = new Date();
        }

        function logTime() {
            const empId = document.getElementById('timeEmployee').value;
            const empName = db.employees.find(e => e.id == empId).name;
            const date = document.getElementById('timeDate').value;
            const tIn = document.getElementById('timeIn').value;
            const tOut = document.getElementById('timeOut').value;

            // Simple hour calc
            let hours = 0;
            if(tIn && tOut) {
                const d1 = new Date(`2000-01-01T${tIn}`);
                const d2 = new Date(`2000-01-01T${tOut}`);
                hours = (d2 - d1) / 1000 / 60 / 60; // hours
            }

            db.attendance.push({ id: Date.now(), empId, empName, date, tIn, tOut, hours: hours.toFixed(2) });
            saveData();
            renderAttendance();
        }

        function renderAttendance() {
            const tbody = document.getElementById('attendanceTable');
            // Show last 10 logs
            const logs = [...db.attendance].reverse().slice(0, 10);
            tbody.innerHTML = logs.map(l => `
                <tr>
                    <td>${l.date}</td>
                    <td>${l.empName}</td>
                    <td>${l.tIn}</td>
                    <td>${l.tOut}</td>
                    <td>${l.hours} hrs</td>
                    <td><span class="status-badge status-active">Present</span></td>
                </tr>
            `).join('');
        }

        // --- 3. PAYROLL PERIODS ---
        function createPeriod() {
            const start = document.getElementById('pStart').value;
            const end = document.getElementById('pEnd').value;
            if(!start || !end) return alert('Dates required');

            db.periods.push({
                id: Date.now(),
                range: `${start} to ${end}`,
                payDate: document.getElementById('pPayDate').value,
                status: 'Open' // Open, Computed, Approved, Paid
            });
            saveData();
            renderPeriods();
            logAction(`Created Payroll Period: ${start} to ${end}`);
        }

        function renderPeriods() {
            const getStatusClass = (s) => {
                if(s === 'Paid') return 'status-paid';
                if(s === 'Approved') return 'status-active';
                if(s === 'Open') return 'status-open';
                return 'status-pending';
            }

            document.getElementById('periodTable').innerHTML = db.periods.map(p => `
                <tr>
                    <td>${p.id}</td>
                    <td>${p.range}</td>
                    <td>${p.payDate}</td>
                    <td><span class="status-badge ${getStatusClass(p.status)}">${p.status}</span></td>
                    <td>-</td>
                </tr>
            `).join('');
        }

        // --- 4. COMPUTATION ---
        function renderCompOptions() {
            const sel = document.getElementById('compPeriod');
            sel.innerHTML = db.periods.filter(p => p.status === 'Open').map(p => `<option value="${p.id}">${p.range}</option>`).join('');
        }

        let currentComputation = [];

        function runComputation() {
            const pId = document.getElementById('compPeriod').value;
            if(!pId) return alert('No Open Period');
            
            // Logic: Assume 22 days/month standard. 
            // Gross = (Monthly / 2) for semi-monthly
            
            currentComputation = db.employees.filter(e => e.status === 'Active').map(emp => {
                const gross = emp.salary / 2; 
                // Deductions (Mock PH Standards)
                const sss = gross * 0.045;
                const phil = gross * 0.04;
                const pagibig = 100;
                const tax = gross * 0.10; // Flat 10% for demo
                const totalDed = sss + phil + pagibig + tax;
                const net = gross - totalDed;

                return {
                    periodId: pId,
                    empId: emp.id,
                    empName: emp.name,
                    gross,
                    deductions: { sss, phil, pagibig, tax },
                    totalDed,
                    net
                };
            });

            const tbody = document.getElementById('compTable');
            tbody.innerHTML = currentComputation.map(c => `
                <tr>
                    <td>${c.empName}</td>
                    <td>$${c.gross.toFixed(2)}</td>
                    <td>$${c.deductions.tax.toFixed(2)}</td>
                    <td>$${(c.deductions.sss + c.deductions.phil + c.deductions.pagibig).toFixed(2)}</td>
                    <td><strong>$${c.net.toFixed(2)}</strong></td>
                </tr>
            `).join('');

            document.getElementById('computationResult').style.display = 'block';
        }

        function submitForValidation() {
            if(currentComputation.length === 0) return;
            const pId = currentComputation[0].periodId;
            
            // Save computed records
            db.payrolls.push(...currentComputation);
            
            // Update Period Status
            const pIndex = db.periods.findIndex(p => p.id == pId);
            db.periods[pIndex].status = 'Pending Approval';
            
            saveData();
            logAction('Submitted payroll for validation');
            document.getElementById('computationResult').style.display = 'none';
            alert('Submitted to Approval Workflow');
            switchTab('payroll-validation');
        }

        // --- 5. VALIDATION ---
        function renderValidation() {
            const pendingPeriods = db.periods.filter(p => p.status === 'Pending Approval');
            const table = document.getElementById('validationTable');
            if(pendingPeriods.length === 0) {
                table.innerHTML = '<tr><td colspan="4">No pending payrolls.</td></tr>';
                return;
            }

            table.innerHTML = `
                <thead><tr><th>Period</th><th>Emp Count</th><th>Action</th></tr></thead>
                <tbody>
                    ${pendingPeriods.map(p => `
                        <tr>
                            <td>${p.range}</td>
                            <td>${db.payrolls.filter(pay => pay.periodId == p.id).length}</td>
                            <td>
                                <button class="btn btn-success" onclick="approvePayroll(${p.id})">Approve</button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            `;
        }

        function approvePayroll(pId) {
            const pIndex = db.periods.findIndex(p => p.id == pId);
            db.periods[pIndex].status = 'Approved';
            saveData();
            renderValidation();
            logAction(`Approved Payroll for period ID: ${pId}`);
            alert('Payroll Approved!');
        }

        // --- 7. RELEASE ---
        function renderReleaseOptions() {
            const sel = document.getElementById('releasePeriod');
            sel.innerHTML = db.periods.filter(p => p.status === 'Approved').map(p => `<option value="${p.id}">${p.range}</option>`).join('');
            
            // Render Release Log
            const logUl = document.getElementById('releaseLog');
            // Filter Audit logs for "Released" keywords
            const releases = db.audit.filter(l => l.includes('Released'));
            logUl.innerHTML = releases.length ? releases.map(r => `<li>${r}</li>`).join('') : '<li>No releases yet.</li>';
        }

        function releaseSalary() {
            const pId = document.getElementById('releasePeriod').value;
            const method = document.getElementById('payMethod').value;
            if(!pId) return alert('No Approved Period');

            const pIndex = db.periods.findIndex(p => p.id == pId);
            db.periods[pIndex].status = 'Paid';
            
            logAction(`Released Period ${pId} via ${method}`);

            saveData();
            alert('Salaries Released Successfully!');
            updateDashboard();
            renderReleaseOptions(); // Refresh list
        }

        // --- 8. PAYSLIPS ---
        function renderPayslipOptions() {
            const empSel = document.getElementById('psEmployee');
            const perSel = document.getElementById('psPeriod');
            
            empSel.innerHTML = db.employees.map(e => `<option value="${e.id}">${e.name}</option>`).join('');
            // Show Paid and Approved periods
            perSel.innerHTML = db.periods.filter(p => p.status === 'Paid' || p.status === 'Approved').map(p => `<option value="${p.id}">${p.range} (${p.status})</option>`).join('');
        }

        function generatePayslip() {
            const empId = document.getElementById('psEmployee').value;
            const pId = document.getElementById('psPeriod').value;
            
            const record = db.payrolls.find(p => p.empId == empId && p.periodId == pId);

            if(!record) {
                alert('No record found for this selection. Has the payroll been computed?');
                return;
            }

            const content = document.getElementById('psContent');
            content.innerHTML = `
                <div class="payslip-row"><span>Employee:</span> <span>${record.empName}</span></div>
                <div class="payslip-row"><span>Period:</span> <span>${db.periods.find(p=>p.id==pId).range}</span></div>
                <hr style="margin: 10px 0; border: 0; border-top: 1px solid #ddd;">
                <div class="payslip-row"><span>Basic Salary (Semi-Monthly):</span> <span>$${record.gross.toFixed(2)}</span></div>
                <div class="payslip-row" style="color:red;"><span>Tax:</span> <span>-$${record.deductions.tax.toFixed(2)}</span></div>
                <div class="payslip-row" style="color:red;"><span>SSS/Phil/HDMF:</span> <span>-$${(record.deductions.sss + record.deductions.phil + record.deductions.pagibig).toFixed(2)}</span></div>
                <div class="payslip-row payslip-total"><span>NET PAY:</span> <span>$${record.net.toFixed(2)}</span></div>
            `;
            
            document.getElementById('payslipView').style.display = 'block';
        }

        // --- DASHBOARD & REPORTS ---
        function updateDashboard() {
            document.getElementById('dash-total-emp').innerText = db.employees.filter(e => e.status === 'Active').length;
            
            // Calculate totals
            let totalPaid = 0;
            let totalTax = 0;
            let totalDed = 0;

            db.payrolls.forEach(p => {
                // Only count paid periods
                const period = db.periods.find(x => x.id == p.periodId);
                if(period && period.status === 'Paid') {
                    totalPaid += p.net;
                    totalTax += p.deductions.tax;
                    totalDed += p.totalDed;
                }
            });

            document.getElementById('dash-total-pay').innerText = '$' + totalPaid.toLocaleString(undefined, {minimumFractionDigits: 2});
            document.getElementById('rep-tax').innerText = '$' + totalTax.toLocaleString(undefined, {minimumFractionDigits: 2});
            document.getElementById('rep-deduct').innerText = '$' + totalDed.toLocaleString(undefined, {minimumFractionDigits: 2});
        }

        // --- ADMIN ---
        function clearData() {
            if(confirm('Are you sure? This cannot be undone.')) {
                localStorage.clear();
                location.reload();
            }
        }
        
        function renderAudit() {
            document.getElementById('auditLog').innerHTML = db.audit.map(a => `<li>${a}</li>`).join('');
        }

        // --- INITIALIZATION ---
        window.addEventListener('load', () => {
            initSampleData(); // LOAD SAMPLE DATA IF EMPTY
            updateDashboard();
            
            // Chart Init
            const barCtx = document.getElementById('barChart');
            if (barCtx) {
                new Chart(barCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Aug', 'Sep', 'Oct', 'Nov'],
                        datasets: [{ label: 'Expenses', data: [12000, 19000, 15000, 22000], backgroundColor: '#a78bfa' }]
                    },
                    options: { maintainAspectRatio: false }
                });
            }
            
            const pieCtx = document.getElementById('pieChart');
            if (pieCtx) {
                new Chart(pieCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Active', 'Resigned'],
                        datasets: [{ data: [4, 1], backgroundColor: ['#10b981', '#ef4444'] }]
                    },
                    options: { maintainAspectRatio: false }
                });
            }
        });
    </script>
</body>
</html>