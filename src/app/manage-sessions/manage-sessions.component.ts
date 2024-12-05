import { Component, OnInit } from '@angular/core';
import { ScheduleService } from '../schedule.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-manage-sessions',
  templateUrl: './manage-sessions.component.html',
  styleUrls: ['./manage-sessions.component.css']
})
export class ManageSessionsComponent implements OnInit {
  sessions: any[] = [];
  searchTerm: string = ''; 

  constructor(private scheduleService: ScheduleService, private router: Router) {}

  ngOnInit(): void {
    this.scheduleService.getSessions().subscribe(data => {
      console.log('Données récupérées:', data); 
      this.sessions = data;
    });
  }

  editSession(sessionId: number): void {
    this.router.navigate(['/edit-session', sessionId]);
    alert('Modifier la session ' + sessionId);
  }

  deleteSession(sessionId: number): void {
    if (confirm('Voulez-vous vraiment supprimer cette session ?')) {
      this.scheduleService.deleteSession(sessionId).subscribe(() => {
        this.sessions = this.sessions.filter(session => session.id !== sessionId);
      });
    }
  }

  filteredSessions() {
    if (!this.searchTerm) {
      return this.sessions; 
    }
  
    return this.sessions.filter(session => 
      Object.keys(session).some(key => {
        const value = session[key];
        if (value && typeof value === 'string') {
          return value.toLowerCase().includes(this.searchTerm.toLowerCase());
        }
        return false;
      })
    );
  }
  
}
