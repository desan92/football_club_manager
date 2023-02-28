import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { PagesRoutingModule } from './pages-routing.module';
import { AppRoutingModule } from '../app-routing.module';

import { HomeComponent } from './home/home.component';
import { SharedModule } from '../shared/shared.module';
import { PerfilComponent } from './perfil/perfil.component';
import { AltresClubsComponent } from './altres-clubs/altres-clubs.component';
import { RouterModule } from '@angular/router';
import { InfoClubComponent } from './info-club/info-club.component';
import { JugadorComponent } from './jugador/jugador.component';
import { EntrenadorComponent } from './entrenador/entrenador.component';
import { CrearPartitComponent } from './crear-partit/crear-partit.component';
import { VeurePartitsComponent } from './veure-partits/veure-partits.component';
import { DadesPartitComponent } from './dades-partit/dades-partit.component';
import { TransferenciesComponent } from './transferencies/transferencies.component';
import { MercatFixatgesComponent } from './mercat-fixatges/mercat-fixatges.component';


@NgModule({
    declarations: [
      HomeComponent,
      PerfilComponent,
      AltresClubsComponent,
      InfoClubComponent,
      JugadorComponent,
      EntrenadorComponent,
      CrearPartitComponent,
      VeurePartitsComponent,
      DadesPartitComponent,
      TransferenciesComponent,
      MercatFixatgesComponent
    ],
    imports: [
      FormsModule,
      ReactiveFormsModule,
      RouterModule,
      AppRoutingModule,
      PagesRoutingModule,
      CommonModule,
      SharedModule
    ]
})
export class PagesModule { }
