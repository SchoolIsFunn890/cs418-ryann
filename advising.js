const viewRecords = document.getElementById("view");
const innerBody = document.getElementById("boody");
const userData = document.createElement("div");
const userDataCourses = document.createElement("div");
const adminDisplay = document.createElement("div");

const lastterm = document.getElementById("lastterm");
const lastgpa = document.getElementById("lastgpa");
const curterm = document.getElementById("curterm");
const sub = document.getElementById("submitButton");


let adminChecker = false;


fetch("advising.php", {
    method: "POST",
})
.then(res => res.text())
.then(text => {
    if (text.includes("admin")) {
		adminChecker = true;
        sub.remove();
        adminStuff();
	    return;
    }

});


async function adminStuff() {
    const response = await fetch("csDepStudents.php");
    const data = await response.json();

    const table = document.createElement("table");
    table.border = "1";
    table.style.marginTop = "10px";
    table.style.borderCollapse = "collapse";

    table.innerHTML = `
        <tr>
            <th>Student Name</th>
            <th>Current Term</th>
            <th>Status</th>
        </tr>
    `;

    data.forEach(student => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${student.first} ${student.last}</td>
            <td>${student.current_term ?? "N/A"}</td>
            <td>${student.status ?? "N/A"}</td>
        `;

        row.addEventListener("click", () => {
            showStudentCoruses(row, student.email);
        });

        table.appendChild(row);
    });

    userData.innerHTML = "";
    userData.appendChild(table);
    innerBody.appendChild(userData);
}

async function showStudentCoruses(row, email) {
    if (row.nextSibling && row.nextSibling.classList.contains("closer")) {
        row.nextSibling.remove();
        return;
    }

    const response = await fetch("getCurrentByUser.php", {
        method: "POST",
        body: new URLSearchParams({ email })
    });

    const courses = await response.json();

    const detailsRow = document.createElement("tr");
    detailsRow.classList.add("closer");

    const cell = document.createElement("td");
    cell.colSpan = 3;

    let html = "<strong>Planned Courses:</strong><br>";

    if (courses.length === 0) {
        html += "<i>No planned courses</i><br><br>";
    } else {
        html += "<ul>";
        courses.forEach(c => {
            html += `<li>${c.course_id} — ${c.level} — ${c.course_name}</li>`;
        });
        html += "</ul>";
    }

    html += `
        <div style="margin-top:10px;">
            <label>Status:</label>
            <select id="status-${email}">
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>

            <br><br>

            <label>Message to Student:</label><br>
            <textarea id="message-${email}" rows="3" style="width:90%;"></textarea>

            <br><br>

            <button id="save-${email}">Save</button>
        </div>
    `;

    cell.innerHTML = html;
    detailsRow.appendChild(cell);
    row.parentNode.insertBefore(detailsRow, row.nextSibling);

    document.getElementById(`save-${email}`).addEventListener("click", async () => {
        const status = document.getElementById(`status-${email}`).value;
        const message = document.getElementById(`message-${email}`).value;
        const term = row.children[1].textContent.trim(); 

        const send = await fetch("updateTermStatusByUser.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, term, status, message })
        });

        const result = await send.json();
        if (result.success) {
            alert("Status updated!");
        }
    });
}






async function loadStudentData() {
    if (adminChecker) return;
    const response = await fetch("getLastAndCurrent.php");
    const data = await response.json();

    if (data) {
        lastterm.placeholder = data.last_term;
        lastgpa.placeholder = data.last_gpa;
        curterm.placeholder = data.current_term;
    } else {
        lastterm.placeholder = "Fall 2024";
        lastgpa.placeholder = "3.5 (1.0-4.0)";
        curterm.placeholder = "Spring 2025";
    }
}

loadStudentData();

const viewCourses = document.getElementById("courses");

let viewing = 0;
viewRecords.addEventListener('click', async () => {
    if (adminChecker) return;
    
    
     if(viewing == 0){
        const response = await fetch("getTerms.php");
        const data = await response.json();
         
         
        const table = document.createElement("table");

        table.border = "1";
        table.style.marginTop = "10px";
        table.style.borderCollapse = "collapse";

        table.innerHTML = `
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Term</th>
                <th>Message</th>

            </tr>
        `;

        data.forEach(course => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${course.date_submitted}</td>
                <td>${course.status}</td>
                <td>${course.term}</td>
                <td>${course.message  ?? "N/A"}</td>
            `;
            table.appendChild(row);
        });
         
      userData.appendChild(table);   
      innerBody.appendChild(userData);            
       viewing = 1;         
     }else {
        viewing = 0;
        innerBody.removeChild(userData);
        userData.innerHTML = "";
    }
 
});

let coursing = 0;
viewCourses.addEventListener('click', async () => {
        if (adminChecker) return;
     if(coursing == 0){
        const response = await fetch("getCourses.php");
        const data = await response.json();
         
		const table = document.createElement("table");

        table.border = "1";
        table.style.marginTop = "10px";
        table.style.borderCollapse = "collapse";

        table.innerHTML = `
            <tr>
                <th>Course ID</th>
                <th>Level</th>
                <th>Course Name</th>
            </tr>
        `;

        data.forEach(course => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${course.course_id}</td>
                <td>${course.level}</td>
                <td>${course.course_name}</td>
            `;
            table.appendChild(row);
        });
         
      userDataCourses.appendChild(table);   
      innerBody.appendChild(userDataCourses);
      coursing = 1;
         
     }else {
        coursing = 0;
        innerBody.removeChild(userDataCourses);
        userDataCourses.innerHTML = "";
    }
 
});


const currentTerm = document.getElementById("currentTerm");
const userDataCurrent = document.createElement("div");
let currenting = 0;

currentTerm.addEventListener('click', async () => {
        if (adminChecker) return;
    if (currenting === 0) {

        const response = await fetch("getCurrent.php");
        const currentData = await response.json();

        const allCoursesResponse = await fetch("getCourseCatalog.php");
        const allCourses = await allCoursesResponse.json();

        const lastTermResponse = await fetch("getCourses.php");
        const lastTermCourses = await lastTermResponse.json();
        const lastTermIDs = lastTermCourses.map(c => Number(c.course_id));

        const table = document.createElement("table");
        table.border = "1";
        table.style.marginTop = "10px";
        table.style.borderCollapse = "collapse";

        table.innerHTML = `
            <tr>
                <th>Course ID</th>
                <th>Level</th>
                <th>Course Name</th>
                <th>Action</th>
            </tr>
        `;

        currentData.forEach(course => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${course.course_id}</td>
                <td>${course.level}</td>
                <td>${course.course_name}</td>
                <td><button class="deleteRow">Delete</button></td>
            `;
            table.appendChild(row);

            row.querySelector(".deleteRow").addEventListener("click", async () => {
                const courseID = Number(course.course_id);

                await fetch("deletePlannedCourse.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ course_id: courseID })
                });

                row.remove();
            });
        });

        const addRowBtn = document.createElement("button");
        addRowBtn.textContent = "Add Course for Current Term";
        addRowBtn.style.marginTop = "10px";

        addRowBtn.addEventListener("click", () => {
            const newRow = document.createElement("tr");

            let options = `<option value="">Select Course</option>`;
            allCourses.forEach(c => {
                const disabled = lastTermIDs.includes(Number(c.course_id)) ? "disabled" : "";
                const label = lastTermIDs.includes(Number(c.course_id))
                    ? `${c.level} - ${c.course_name} (Taken Last Term)`
                    : `${c.level} - ${c.course_name}`;
                options += `<option value="${c.course_id}" ${disabled}>${label}</option>`;
            });

            newRow.innerHTML = `
                <td>
                    <select class="courseSelect">${options}</select>
                </td>
                <td class="levelCell"></td>
                <td class="nameCell"></td>
                <td><button class="deleteRow">Delete</button></td>
            `;

            newRow.querySelector(".courseSelect").addEventListener("change", function () {
                const selected = allCourses.find(c => c.course_id == this.value);
                if (selected) {
                    newRow.querySelector(".levelCell").textContent = selected.level;
                    newRow.querySelector(".nameCell").textContent = selected.course_name;
                }
            });

            newRow.querySelector(".deleteRow").addEventListener("click", async () => {

                const select = newRow.querySelector(".courseSelect");
                const idCell = newRow.children[0];

                let courseID = null;

                if (select) {
                    courseID = Number(select.value);
                } else {
                    courseID = Number(idCell.textContent.trim());
                }

                if (courseID) {
                    await fetch("deletePlannedCourse.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ course_id: courseID })
                    });
                }

                newRow.remove();
            });

            table.appendChild(newRow);
        });

        const saveBtn = document.createElement("button");
        saveBtn.textContent = "Save Current Term Courses";
        saveBtn.style.marginTop = "10px";
        saveBtn.style.marginLeft = "10px";

        saveBtn.addEventListener("click", async () => {
            const rows = table.querySelectorAll("tr");
            const planned = [];

            rows.forEach((row, index) => {
                if (index === 0) return;

                const select = row.querySelector(".courseSelect");
                const idCell = row.children[0];

                let courseID = null;

                if (select) {
                    courseID = Number(select.value);
                } else {
                    courseID = Number(idCell.textContent.trim());
                }

                if (courseID) {
                    planned.push(courseID);
                }
            });

            const send = await fetch("saveCurrentTerm.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ courses: planned })
            });

            const result = await send.text();
            alert(result);
        });

        userDataCurrent.appendChild(table);
        userDataCurrent.appendChild(addRowBtn);
        userDataCurrent.appendChild(saveBtn);
        innerBody.appendChild(userDataCurrent);

        currenting = 1;

    } else {
        currenting = 0;
        innerBody.removeChild(userDataCurrent);
        userDataCurrent.innerHTML = "";
    }
});
