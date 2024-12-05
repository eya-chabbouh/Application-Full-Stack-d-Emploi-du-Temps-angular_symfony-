import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-edit-session',
  templateUrl: './edit-session.component.html',
  styleUrls: ['./edit-session.component.css']
})
export class EditSessionComponent implements OnInit {
  sessionId!: number;
  sessionData: any = {};

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private http: HttpClient
  ) {}

  ngOnInit(): void {
    this.sessionId = Number(this.route.snapshot.paramMap.get('id'));
    this.loadSession();
  }

  loadSession(): void {
    this.http.get(`http://localhost:8000/sessions/${this.sessionId}`).subscribe({
      next: (data: any) => {
        this.sessionData = data;
      },
      error: (err) => {
        console.error('Erreur lors du chargement de la session', err);
        alert('Impossible de charger la session. Vérifiez votre connexion ou contactez un administrateur.');
      }
    });
  }
  

  saveSession(): void {
    this.http.put(`http://localhost:8000/sessions/${this.sessionId}`, this.sessionData).subscribe({
      next: () => {
        alert('Session mise à jour avec succès!');
        this.router.navigate(['/manage-sessions']);
      },
      error: (err) => {
        console.error('Erreur lors de la mise à jour de la session', err);
        alert('Erreur lors de la mise à jour. Veuillez réessayer.');
      }
    });
  }
  

  cancelEdit(): void {
    this.router.navigate(['/manage-sessions']);
  }
}
