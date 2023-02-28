import { Component, ElementRef, SimpleChanges, ViewChild } from '@angular/core';
import { JugadorService } from '../../services/jugador.service';
import { Usuari } from '../../models/usuari.model';
import { AuthService } from '../../services/auth.service';
import { Jugador } from '../../interfaces/jugador.interface';
import { EntrenadorService } from '../../services/entrenador.service';
import { Entrenador } from '../../interfaces/entrenador.interface';
import { TransferenciesService } from '../../services/transferencies.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-mercat-fixatges',
  templateUrl: './mercat-fixatges.component.html',
  styleUrls: ['./mercat-fixatges.component.css']
})
export class MercatFixatgesComponent {

  public opcions = ['Jugadors Club', 'Jugadors Mercat', 'Entrenador Club', 'Entrenadors Mercat'];
  public selectedMercat: string = 'Jugadors Club';
  public usuari!: Usuari;

  public jugadors!: Jugador[];
  public pageJugadorsClub = 1;
  public maxbutons!: any;
  public buton: number[] = [];

  public jugadorsMercat!: Jugador[];
  public pageJugadorsClubMercat = 1;
  public maxbutonsMercat!: any;
  public butonMercat: number[] = [];

  public entrenador!: Entrenador[];

  public entrenadorMercat!: Entrenador[];
  public pageEntrenadorClubMercat = 1;
  public maxbutonsMercatEnt!: any;
  public butonMercatEnt: number[] = [];

  constructor(private jugadorService: JugadorService,
              private authService: AuthService,
              private entrenadorService: EntrenadorService,
              private transferenciaService: TransferenciesService){

    this.usuari = this.authService.usuari;
    this.onSelected();
    
  }

  selectInformacio(){

    if(this.selectedMercat === 'Jugadors Club')
    {
      this.jugadorService.obtenirAllJugadorsClub(this.usuari.club_id, this.pageJugadorsClub).subscribe( (resp: any) => {
        //console.log(resp);
        this.jugadors = resp.jugadors.data;
        this.maxbutons = resp.jugadors.last_page;
        //console.log(this.jugadors);
        this.buton = Array(resp.jugadors.last_page).fill(0).map((x,i)=>i);
      });
    }
    else if(this.selectedMercat === 'Jugadors Mercat')
    {
      this.jugadorService.obtenirJugadorsDiferentsClub(this.usuari.club_id, this.pageJugadorsClubMercat).subscribe( (resp: any) => {
        //console.log(resp);
        this.jugadorsMercat = resp.jugadors.data;
        this.maxbutonsMercat = resp.jugadors.last_page;
        //console.log(this.jugadorsMercat);
        this.butonMercat = Array(resp.jugadors.last_page).fill(0).map((x,i)=>i);
      });
    }
    else if(this.selectedMercat === 'Entrenador Club')
    {
      this.entrenadorService.obtenirAllEntrenadorClub().subscribe( (resp: any) => {
        //console.log(resp);
        this.entrenador = resp.entrenador;
      });
    }
    else if(this.selectedMercat === 'Entrenadors Mercat')
    {
      this.entrenadorService.obtenirAllEntrenadorDifferentClub(this.pageEntrenadorClubMercat).subscribe( (resp: any) => {
        console.log(resp);
        this.entrenadorMercat = resp.entrenadors.data;
        this.maxbutonsMercatEnt = resp.entrenadors.last_page;
        this.butonMercatEnt = Array(resp.entrenadors.last_page).fill(0).map((x,i)=>i);
      });
    }

  }

  onSelected(): void {
    console.log(this.selectedMercat);
    this.selectInformacio();
	}

  numberPage(page: number){

      if(this.selectedMercat === 'Jugadors Club')
      {
        if(page < 1)
        {
          page = 1;
        }
        else if(page > this.maxbutons)
        {
          page = this.maxbutons;
        }

        if(this.pageJugadorsClub !== page)
        {
          this.pageJugadorsClub = page;
          this.selectInformacio();
        }
        //console.log(this.pageJugadorsClub);
      }
      else if(this.selectedMercat === 'Jugadors Mercat')
      {
        if(page < 1)
        {
          page = 1;
        }
        else if(page > this.maxbutonsMercat)
        {
          page = this.maxbutonsMercat;
        }

        if(this.pageJugadorsClubMercat !== page)
        {
          this.pageJugadorsClubMercat = page;
          this.selectInformacio();
        }
      }
      else if(this.selectedMercat === 'Entrenadors Mercat')
      {
        if(page < 1)
        {
          page = 1;
        }
        else if(page > this.maxbutonsMercatEnt)
        {
          page = this.maxbutonsMercatEnt;
        }

        if(this.pageEntrenadorClubMercat !== page)
        {
          this.pageEntrenadorClubMercat = page;
          this.selectInformacio();
        }
      }
  }
  
  ventaJugador(jugador: Jugador){
    //console.log(jugador);
    this.transferenciaService.ventaJugador(this.usuari.club_id, jugador.id).subscribe( (resp: any) => {
      //console.log(resp);
      Swal.fire('Actualitzat', resp.msg, 'success');
    }, (err) => {
      Swal.fire('Fallida', err.error.msg, 'error');
    });
  }

  comprarJugador(jugador: Jugador){
    //console.log(jugador);
    this.transferenciaService.compraJugador(jugador.club_id, jugador.id, this.usuari.club_id).subscribe( (resp: any) => {
      //console.log(resp);
      Swal.fire('Actualitzat', resp.msg, 'success');
    }, (err) => {
      Swal.fire('Fallida', err.error.msg, 'error');
    });
  }

  ventaEntrenador(entrenador: Entrenador){
    //console.log(entrenador);
    this.transferenciaService.ventaEntrenador(this.usuari.club_id, entrenador.id).subscribe( (resp: any) => {
      //console.log(resp);
      Swal.fire('Actualitzat', resp.msg, 'success');
    }, (err) => {
      Swal.fire('Fallida', err.error.msg, 'error');
    });
  }

  comprarEntrenador(entrenador: Entrenador){
    //console.log(jugador);
    this.transferenciaService.compraEntrenador(entrenador.club_id, entrenador.id, this.usuari.club_id).subscribe( (resp: any) => {
      //console.log(resp);
      Swal.fire('Actualitzat', resp.msg, 'success');
    }, (err) => {
      Swal.fire('Fallida', err.error.msg, 'error');
    });
  }

}
