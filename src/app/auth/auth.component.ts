import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { environment } from '../../environments/environments';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-auth',
  templateUrl: './auth.component.html',
  styleUrls: ['./auth.component.css']
})
export class AuthComponent {
  email: string = '';
  password: string = '';
  errorMessage: string = ''; 
  loading: boolean = false; 

  constructor(private http: HttpClient, private router: Router) {}

  onLogin() {
    this.errorMessage = '';
    
    if (!this.email || !this.password) {
      this.errorMessage = 'Veuillez remplir tous les champs.';
      return; 
    }

    this.loading = true; 

    const body = { email: this.email, password: this.password };

    this.http.post(`${environment.apiUrl}/login`, body).subscribe(
      (response: any) => {
        this.loading = false; 
        console.log('Connexion réussie', response);
        if (response.role === 'ROLE_ADMIN') {
          this.router.navigate(['/dashboard']);
        } else {
          this.errorMessage = 'Accès interdit. Vous n\'avez pas les droits nécessaires.';
        }
      },
      (error) => {
        this.loading = false; 
        console.error('Erreur de connexion', error);
        
        if (error.status === 400) {
          this.errorMessage = 'Adresse e-mail ou mot de passe incorrect.';
        } else if (error.status === 0) {
          this.errorMessage = 'Problème de connexion au serveur. Veuillez réessayer plus tard.';
        } else {
          this.errorMessage = 'Une erreur est survenue, veuillez réessayer.';
        }
      }
    );
  }
}
