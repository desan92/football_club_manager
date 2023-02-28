import { Component } from '@angular/core';
import { Jugador } from 'src/app/interfaces/jugador.interface';
import { Club, Usuari } from 'src/app/models/usuari.model';
import { ClubService } from 'src/app/services/club.service';
import Swal from 'sweetalert2';
import { AuthService } from '../../services/auth.service';
import { JugadorService } from '../../services/jugador.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent {

  public club!: Club;
  public usuari!: Usuari;
  public manager = '';
  public jugadors: Jugador[] = [];
  public page = 1;
  public maxbutons!: any;
  public buton: number[] = [];

  constructor(private clubService: ClubService,
              private authService: AuthService,
              private jugadorService: JugadorService){
    
  }

  ngOnInit(): void {
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.
    this.usuari = this.authService.usuari;
    //console.log(this.usuari);
    this.cargarClub(this.usuari.club_id, this.page);
    this.numberPage(this.page);
  }

  cargarClub(id:number, page:number){
    this.clubService.onlyMyClub(id, page).subscribe( (resp: any) => {
        console.log(resp);
        this.club = resp.club;
        this.manager = resp.manager.nom + ' ' + resp.manager.cognom;
        //console.log(this.manager);
        this.jugadors = resp.jugadors.data;
        console.log(this.jugadors);
        this.maxbutons = resp.jugadors.last_page;
        this.buton = Array(resp.jugadors.last_page).fill(0).map((x,i)=>i);
      });
  } 

  nouJugador(){
    this.jugadorService.nouJugador().subscribe( resp => {
      console.log(resp);
      Swal.fire('Promoted', 'Jugador pujat de la cantera', 'success');
      
      this.cargarClub(this.usuari.club_id, this.page);
    } , (err) => {
      Swal.fire('Promoted', err.error.msg, 'success');
    });
  }

  numberPage(page: number){

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
      console.log(this.page);
      this.cargarClub(this.usuari.club_id, this.page);
    }

  }

}
