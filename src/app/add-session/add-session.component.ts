import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-add-session',
  templateUrl: './add-session.component.html',
  styleUrls: ['./add-session.component.css']
})
export class AddSessionComponent {
  sessionDate: string = '';
  startTime: string = '';
  endTime: string = '';
  session: string = '';
  subjectId: number = 0;
  teacherId: number = 0;
  roomId: number = 0;
  classId: number = 0;

  constructor(private router: Router, private http: HttpClient) {}

  onSubmit() {
    const sessionData = {
      session_date: this.sessionDate,
      start_time: this.startTime,
      end_time: this.endTime,
      session: this.session,
      subject_id: this.subjectId,
      teacher_id: this.teacherId,
      room_id: this.roomId,
      class_id: this.classId
    };

    this.http.post('http://localhost:8000/sessions', sessionData).subscribe(response => {
      console.log('Session added successfully', response);
      this.router.navigate(['/manage-sessions']);  
    }, error => {
      console.error('Error adding session:', error);
    });
  }
}
