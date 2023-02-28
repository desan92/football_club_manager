import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AltresClubsComponent } from './altres-clubs.component';

describe('AltresClubsComponent', () => {
  let component: AltresClubsComponent;
  let fixture: ComponentFixture<AltresClubsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AltresClubsComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AltresClubsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
