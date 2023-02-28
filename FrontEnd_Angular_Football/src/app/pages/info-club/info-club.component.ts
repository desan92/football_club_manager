import { Component } from '@angular/core';
import { Jugador } from 'src/app/interfaces/jugador.interface';
import { Club } from 'src/app/models/usuari.model';
import { ClubService } from 'src/app/services/club.service';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-info-club',
  templateUrl: './info-club.component.html',
  styleUrls: ['./info-club.component.css']
})
export class InfoClubComponent {
  public club!: Club;
  public manager = '';
  public jugadors: Jugador[] = [];

  constructor(private clubService: ClubService,
              private ActivatedRoute: ActivatedRoute){
    
  }

  ngOnInit(): void {
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.
    this.ActivatedRoute.params.subscribe( ({id}) => {
      this.cargarClub(id);
      
      //console.log(id);
    })
    
  }

  cargarClub(id: number){
    this.clubService.onlyMyClub(id, 1)
      .subscribe((resp: any) => {
        console.log(resp);
        this.club = resp.club;
        this.manager = resp.manager.nom + ' ' + resp.manager.cognom;
        //console.log(this.manager);
        this.jugadors = resp.jugadors;
        //console.log(this.jugadors);
      });
  }

}
