import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { AuthComponent } from './auth/auth.component';
import { DashboardComponent } from './dashboard/dashboard.component';

import { ManageSessionsComponent } from './manage-sessions/manage-sessions.component';
import { ScheduleService } from './schedule.service';
import { EditSessionComponent } from './edit-session/edit-session.component'; 
import { RouterModule } from '@angular/router';
import { AddSessionComponent } from './add-session/add-session.component';



@NgModule({
  declarations: [
    AppComponent,
    AuthComponent,
    DashboardComponent,
    ManageSessionsComponent,
    EditSessionComponent,
    AddSessionComponent,  

  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule, 
    AppRoutingModule, 
  ],
  providers: [ScheduleService], 
  bootstrap: [AppComponent]
})
export class AppModule { }
