import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Stats } from 'src/app/interfaces/stats.interface';
import { PartitsService } from '../../services/partits.service';
import { Club } from '../../models/usuari.model';

@Component({
  selector: 'app-dades-partit',
  templateUrl: './dades-partit.component.html',
  styleUrls: ['./dades-partit.component.css']
})
export class DadesPartitComponent {

  public resultat!: string;
  public golsLocals!: string;
  public golsVisitants!: string;
  public local!: Club;
  public visitant!: Club;
  public stats: Stats[] = [];
  public statLocal: Stats[] = [];
  public statVisitant: Stats[] = [];

  constructor(private partitsService: PartitsService,
              private ActivatedRoute: ActivatedRoute){}

  ngOnInit(): void {
    //Called after the constructor, initializing input properties, and the first call to ngOnChanges.
    //Add 'implements OnInit' to the class.
    this.ActivatedRoute.params.subscribe( ({id}) => {
      //console.log(id);
      this.allStats(id)
    })
  }

  allStats(id:number){
    this.partitsService.allGamesStats(id).subscribe((resp: any) => {
      console.log(resp);
      this.resultat = resp.partit.resultat;

      this.golsLocals = this.resultat.substring(0, this.resultat.indexOf("-"));
      this.golsVisitants = this.resultat.substring(this.resultat.indexOf('-') + 1);

      this.local = resp.equipLocal;
      this.visitant = resp.equipVisitant;
      
      this.stats = resp.estadistiques;
      console.log(this.stats);
      this.controlStats(this.stats);
    });
  }

  controlStats(stats: Stats[]){
    for(var i = 0; i < stats.length; i++)
    {
      if(this.local.id === stats[i].club_id)
      {
        this.statLocal.push(stats[i]);
      }
      else
      {
        this.statVisitant.push(stats[i]);
      }
    }
    
  }

}
