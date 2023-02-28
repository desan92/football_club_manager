import { Component, SimpleChanges } from '@angular/core';
import { Club, Usuari } from 'src/app/models/usuari.model';
import { ClubService } from '../../services/club.service';
import { AuthService } from '../../services/auth.service';
import { PartitsService } from '../../services/partits.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-crear-partit',
  templateUrl: './crear-partit.component.html',
  styleUrls: ['./crear-partit.component.css']
})
export class CrearPartitComponent {

  public clubs: Club[] = []; 
  public usuari!: Usuari;
  public localclub!: Club;
  public visitantclub!: Club;

  constructor(private clubService: ClubService, 
              private authService: AuthService,
              private partitsSercice: PartitsService){}

  ngOnInit(): void {
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.
    this.usuari = this.authService.usuari;
    this.allclubs();
    
  }
  
  /* ngOnChanges(changes: SimpleChanges): void {
    //Called before any other lifecycle hook. Use it to inject dependencies, but avoid any serious work here.
    //Add '${implements OnChanges}' to the class.
    console.log(this.visitantclub);
  } */

  allclubs(){
    this.clubService.allClubs().subscribe((resp: any) => {
      console.log(resp);
      this.clubs = resp.clubs;
      console.log(this.clubs);
      this.escutLocal(this.usuari.club_id, this.clubs);
    });
  }

  escutLocal(id_club: number, clubs: Club[]){

    for(var i = 0; i < clubs.length; i++)
    {
      if(id_club === clubs[i].id)
      {
        this.localclub = clubs[i];
        clubs.splice(i, 1)
      }
      this.visitantclub = clubs[0];
    }

  }

  crearPartit()
  {
    this.partitsSercice.crearPartit(this.localclub.id, this.visitantclub.id).subscribe( (resp: any) => {
      //console.log(resp);
      Swal.fire('Creat', resp.msg, 'success');
    }, (err: any) => {
      //console.log(err);
      Swal.fire('No creat', err.error.msg, 'error');
    })
  }

}
