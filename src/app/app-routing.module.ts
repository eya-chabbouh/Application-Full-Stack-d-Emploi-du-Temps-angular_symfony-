import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthComponent } from './auth/auth.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { ManageSessionsComponent } from './manage-sessions/manage-sessions.component';
import { EditSessionComponent } from './edit-session/edit-session.component';
import { AddSessionComponent } from './add-session/add-session.component'; 


const routes: Routes = [
  { path: 'auth', component: AuthComponent },
  { path: '', redirectTo: '/auth', pathMatch: 'full' },
  { path: 'dashboard', component: DashboardComponent },
  { path: 'edit-session/:id', component: EditSessionComponent }, 
  { path: 'add-session', component: AddSessionComponent },  

  { path: 'manage-sessions', component: ManageSessionsComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
