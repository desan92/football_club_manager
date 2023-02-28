import { ComponentFixture, TestBed } from '@angular/core/testing';

import { VeurePartitsComponent } from './veure-partits.component';

describe('VeurePartitsComponent', () => {
  let component: VeurePartitsComponent;
  let fixture: ComponentFixture<VeurePartitsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ VeurePartitsComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(VeurePartitsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
