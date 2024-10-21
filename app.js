document.addEventListener('DOMContentLoaded', function() {
    fetch('get_students.php')
        .then(response => response.json())
        .then(data => {
            let tableBody = document.querySelector('tbody');
            data.forEach(student => {
                let row = document.createElement('tr');
                
                row.innerHTML = `
                    <td>${student.student_name}</td>
                    <td>${student.father_name}</td>
                    <td>
                        <button class="present" onclick="markAttendance(${student.id}, 'Present')">Present</button>
                        <button class="absent" onclick="markAttendance(${student.id}, 'Absent')">Absent</button>
                    </td>
                `;

                tableBody.appendChild(row);
            });
        });
});

function markAttendance(studentId, status) {
    fetch('save_attendance.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ student_id: studentId, status: status })
    }).then(response => response.text())
      .then(data => {
          alert(data);
      });
}
