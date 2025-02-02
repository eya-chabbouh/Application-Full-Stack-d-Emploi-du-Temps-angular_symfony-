import { Component, OnInit } from '@angular/core';
import { DashboardService } from '../dashboard.service'; 

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {
  stats: any = {};

  constructor(private dashboardService: DashboardService) {} 

  ngOnInit(): void {
    this.dashboardService.getStats().subscribe({
      next: (data) => {
        this.stats = data; 
      },
      error: (err) => {
        console.error('Erreur lors de la récupération des statistiques', err);
      }
    });
  }
}
