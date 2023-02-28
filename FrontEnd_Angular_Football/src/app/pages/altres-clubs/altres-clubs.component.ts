import { Component } from '@angular/core';
import { Club } from 'src/app/interfaces/club.interface';
import { ClubService } from 'src/app/services/club.service';

@Component({
  selector: 'app-altres-clubs',
  templateUrl: './altres-clubs.component.html',
  styleUrls: ['./altres-clubs.component.css']
})
export class AltresClubsComponent {

  public clubs: Club[] = [];

  constructor(private clubService: ClubService){
    
  }

  ngOnInit(): void {
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.
    this.cargarClubs();
    
  }

  cargarClubs(){
    this.clubService.allClubs()
      .subscribe((resp: any) => {
        console.log(resp);
        this.clubs = resp.clubs;
      });
  }

}
