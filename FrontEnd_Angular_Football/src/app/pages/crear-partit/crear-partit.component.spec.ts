import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CrearPartitComponent } from './crear-partit.component';

describe('CrearPartitComponent', () => {
  let component: CrearPartitComponent;
  let fixture: ComponentFixture<CrearPartitComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CrearPartitComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CrearPartitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
