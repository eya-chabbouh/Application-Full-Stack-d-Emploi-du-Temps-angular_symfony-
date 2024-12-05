import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ScheduleService {
  private apiUrl = 'http://127.0.0.1:8000/sessions'; 
  private statsUrl = 'http://127.0.0.1:8000/api/dashboard/stats'; 

  

  constructor(private http: HttpClient) {}

  getSessions(): Observable<any[]> {
    return this.http.get<any[]>(this.apiUrl);
  }

  deleteSession(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }

  getDashboardStats(): Observable<any> {
    return this.http.get<any>(this.statsUrl);
  }

}

