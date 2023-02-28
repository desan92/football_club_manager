import { Component } from '@angular/core';
import { TransferenciesService } from 'src/app/services/transferencies.service';
import { AuthService } from '../../services/auth.service';
import { Usuari } from '../../models/usuari.model';
import { TransferJugador } from '../../interfaces/transferenciaJugador.interface';
import { TransferEntrenador } from '../../interfaces/transferenciaEntrenador.interface';

@Component({
  selector: 'app-transferencies',
  templateUrl: './transferencies.component.html',
  styleUrls: ['./transferencies.component.css']
})
export class TransferenciesComponent {

  public usuari!: Usuari;
  public transferencies: TransferJugador[] = [];
  public maxbutons!: any;
  public page = 1;
  public buton: number[] = [];

  public transferenciesEnt: TransferEntrenador[] = [];
  public maxbutonsEnt!: any;
  public pageEnt = 1;
  public butonEnt: number[] = [];

  constructor(private TransferenciesService: TransferenciesService,
              private authservice: AuthService){}

  ngOnInit(): void {
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.
    this.usuari = this.authservice.usuari;
    this.transferenciesJugadorsClub(this.usuari.club_id, this.page);
    this.transferenciesEntrenadorClub(this.usuari.club_id, this.pageEnt);
  }

  transferenciesJugadorsClub(id:number, page:number ){
    this.TransferenciesService.transferenciesClub(id, page).subscribe( (resp: any) => {
      //console.log(resp);
      this.maxbutons = resp.jugador.last_page;
      //console.log(this.maxbutons); 
      this.buton = Array(resp.jugador.last_page).fill(0).map((x,i)=>i);
      //console.log(this.buton);
      this.transferencies = resp.jugador.data;
      //console.log(this.transferencies);
    });
  }

  transferenciesEntrenadorClub(id:number, page:number ){
    this.TransferenciesService.transferenciesEntrenadorClub(id, page).subscribe( (resp: any) => {
      console.log(resp);
      this.maxbutonsEnt = resp.entrenador.last_page;
      //console.log(this.maxbutons); 
      this.butonEnt = Array(resp.entrenador.last_page).fill(0).map((x,i)=>i);
      //console.log(this.buton);
      this.transferenciesEnt = resp.entrenador.data;
      //console.log(this.transferencies);
    });
  }

  numberPage(page: number, type: string){

    if(type === 'Jugador')
    {
      if(page < 1)
      {
        page = 1;
      }
      else if(page > this.maxbutons)
      {
        page = this.maxbutons;
      } 

      if(this.page !== page)
      {
        this.page = page;
        this.transferenciesJugadorsClub(this.usuari.club_id, this.page);
      }
    }
    else if(type === 'entrenador')
    {
      if(page < 1)
      {
        page = 1;
      }
      else if(page > this.maxbutonsEnt)
      {
        page = this.maxbutonsEnt;
      } 

      if(this.pageEnt !== page)
      {
        this.pageEnt = page;
        this.transferenciesEntrenadorClub(this.usuari.club_id, this.pageEnt);
      }
    }
    
  }



}
