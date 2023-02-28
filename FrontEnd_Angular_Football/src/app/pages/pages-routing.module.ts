import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from '../guards/auth.guard';
import { HomeComponent } from './home/home.component';
import { AltresClubsComponent } from './altres-clubs/altres-clubs.component';
import { InfoClubComponent } from './info-club/info-club.component';
import { EntrenadorComponent } from './entrenador/entrenador.component';
import { JugadorComponent } from './jugador/jugador.component';
import { CrearPartitComponent } from './crear-partit/crear-partit.component';
import { VeurePartitsComponent } from './veure-partits/veure-partits.component';
import { DadesPartitComponent } from './dades-partit/dades-partit.component';
import { TransferenciesComponent } from './transferencies/transferencies.component';
import { MercatFixatgesComponent } from './mercat-fixatges/mercat-fixatges.component';

const routes: Routes = [
  {
    path: 'home',
    component: HomeComponent,
    canActivate: [AuthGuard],
    //el canLoad s'utilitza per veure si el laziload (loadChildren) pot carregar
    canLoad: [AuthGuard]
  },
  {
    path: 'entrenador',
    component: EntrenadorComponent,
    canActivate: [AuthGuard],
    //el canLoad s'utilitza per veure si el laziload (loadChildren) pot carregar
    canLoad: [AuthGuard]
  },
  {
    path: 'jugador/:id',
    component: JugadorComponent,
    canActivate: [AuthGuard],
    //el canLoad s'utilitza per veure si el laziload (loadChildren) pot carregar
    canLoad: [AuthGuard]
  },
  {
    path: 'altresClubs',
    component: AltresClubsComponent,
    canActivate: [AuthGuard],
    //el canLoad s'utilitza per veure si el laziload (loadChildren) pot carregar
    canLoad: [AuthGuard]
  },
  {
    path: 'infoClub/:id',
    component: InfoClubComponent,
    canActivate: [AuthGuard],
    //el canLoad s'utilitza per veure si el laziload (loadChildren) pot carregar
    canLoad: [AuthGuard]
  },
  {
    path: 'crearPartit',
    component: CrearPartitComponent,
    canActivate: [AuthGuard],
    //el canLoad s'utilitza per veure si el laziload (loadChildren) pot carregar
    canLoad: [AuthGuard]
  },
  {
    path: 'veurePartits',
    component: VeurePartitsComponent,
    canActivate: [AuthGuard],
    //el canLoad s'utilitza per veure si el laziload (loadChildren) pot carregar
    canLoad: [AuthGuard]
  },
  {
    path: 'dadesPartit/:id',
    component: DadesPartitComponent,
    canActivate: [AuthGuard],
    //el canLoad s'utilitza per veure si el laziload (loadChildren) pot carregar
    canLoad: [AuthGuard]
  },
  {
    path: 'transferencies',
    component: TransferenciesComponent,
    canActivate: [AuthGuard],
    //el canLoad s'utilitza per veure si el laziload (loadChildren) pot carregar
    canLoad: [AuthGuard]
  },
  {
    path: 'mercatFixatges',
    component: MercatFixatgesComponent,
    canActivate: [AuthGuard],
    //el canLoad s'utilitza per veure si el laziload (loadChildren) pot carregar
    canLoad: [AuthGuard]
  },
  
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PagesRoutingModule { }