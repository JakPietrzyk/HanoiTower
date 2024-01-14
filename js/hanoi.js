const svgns = "http://www.w3.org/2000/svg";
const tower = document.getElementById('tower');
const xhr = new XMLHttpRequest();
const url = "http://localhost:4010//~1pietrzyk/zad/hanoiTower/php/";

const svgWidth = 600;
const towerWidth = 150;
const towerHeight = 350;
const towerSpacing = 30;
const poleWidth = 10;
const diskWidth = 25
const diskHeight = 20;
let numberOfDisks = parseInt(document.getElementById('diskNumber').value);
let animationSpeed = parseInt(document.getElementById('animationSpeed').value);




let cancel = false;
let disks = [];
const pegs = [[], [], []];
let isSolving = false;

function createTower() {
  const totalTowerWidth = 3 * towerWidth + 2 * towerSpacing;
  const startingX = (svgWidth - totalTowerWidth) / 2;

  for (let i = 0; i < 3; i++) {
    const pole = document.createElementNS(svgns, 'rect');
    pole.setAttribute('x', startingX + i * (towerWidth + towerSpacing) + towerWidth / 2 - poleWidth / 2);
    pole.setAttribute('y', 400 - towerHeight);
    pole.setAttribute('width', poleWidth);
    pole.setAttribute('height', towerHeight);
    pole.setAttribute('fill', '#000');
    tower.appendChild(pole);
    console.log(`${i} ${startingX + i * (towerWidth + towerSpacing) + towerWidth / 2 - poleWidth / 2}`);
  }
}

function createDisks() {
  const colors = ["red", "orange", "yellow", "aqua", "green", "blue", "purple"]; 

  for (let i = numberOfDisks - 1; i >= 0; i--) {
    const disk = document.createElementNS(svgns, 'rect');
    const width = diskWidth * (i + 1);
    disk.setAttribute('x', (towerWidth - width) / 2 + (svgWidth - towerWidth * 3 - towerSpacing * 2) / 2);
    disk.setAttribute('y', 400 - diskHeight * (numberOfDisks - i));
    disk.setAttribute('width', width);
    disk.setAttribute('height', diskHeight);
    disk.setAttribute('fill', colors[i % colors.length]);
    tower.appendChild(disk);
    pegs[0].push(disk);
  }
}

async function resetAnimation() {
  cancel = true;
  while (isSolving) {
    await delay(100); 
  }

  pegs.forEach(peg => {
    while (peg.length > 0) {
      const disk = peg.pop();
      disk.remove();
    }
  });
  while (tower.firstChild) {
    tower.removeChild(tower.firstChild);
  }
  cancel = false;

  createTower();
  createDisks();
}
function updateNumberOfDisks() {
  numberOfDisks = parseInt(document.getElementById('diskNumber').value);
  resetAnimation();
}

function updateAnimationSpeed() {
  animationSpeed = 12 - parseInt(document.getElementById('animationSpeed').value);
}


function delay(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function moveDisk(fromPeg, toPeg) {
  const currentDisks = pegs[fromPeg];
  const nextDisks = pegs[toPeg];

  if (currentDisks.length === 0 || cancel) {
    return;
  }

  const diskToMove = currentDisks.pop();
  const diskWidth = diskToMove.width.baseVal.value;
  const diskHeight = diskToMove.height.baseVal.value;

  // const newX = toPeg * 200 + 75 - diskWidth / 2;
  const newY = 380 - (nextDisks.length) * diskHeight;

  const newX = toPeg * towerWidth + (toPeg) * towerSpacing + (towerWidth - diskWidth) / 2 + (svgWidth - towerWidth * 3 - towerSpacing * 2) / 2

  const animateDisk = () => {
    const currentX = diskToMove.x.baseVal.value;
    const currentY = diskToMove.y.baseVal.value;
    var xIterator = (newX - currentX) / animationSpeed;
    var yIterator = (newY - currentY) / animationSpeed;

    console.log(`Move disk from Peg ${fromPeg}(${currentY}) to Peg ${toPeg}(${currentY})`);
    if (Math.abs(currentY - newY) > 0.1 || Math.abs(currentX - newX) > 0.1) {
      diskToMove.y.baseVal.value = currentY + yIterator;
      diskToMove.x.baseVal.value = currentX + xIterator;
      requestAnimationFrame(animateDisk);
    } else {
      diskToMove.x.baseVal.value = newX;
      diskToMove.y.baseVal.value = newY;
      pegs[toPeg].push(diskToMove);
    }
  };

  await new Promise(resolve => {
    animateDisk();
    const checkCompletion = () => {
      if (diskToMove.x.baseVal.value === newX && diskToMove.y.baseVal.value === newY) {
        resolve();
      } else {
        setTimeout(checkCompletion, 16);
      }
    };
    checkCompletion();
  });

}

async function solveTowerOfHanoi(numberOfDisks, A, B, C) {
  if (numberOfDisks > 0 && !cancel) {
    await solveTowerOfHanoi(numberOfDisks - 1, A, C, B);
    await moveDisk(A, C);
    await solveTowerOfHanoi(numberOfDisks - 1, B, A, C);
  }
}
function setUserPreferences() {
  if (isSessionSet) {
    xhr.open("GET", url + "getPreferences", true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.addEventListener("load", e => {
      if (xhr.status == 200) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.status === 'ok' && response.preferences) {
            numberOfDisks = response.preferences.numberOfDisks;
            animationSpeed = response.preferences.animationSpeed;
            document.getElementById('diskNumber').value = numberOfDisks;
            document.getElementById('animationSpeed').value = animationSpeed;
            updateAnimationSpeed();
            updateNumberOfDisks();
          }
        } catch (e) {
          alert('Error: Unable to parse the response.');
          console.error('Error response:', xhr.responseText);
        }
      } else {
        try {
          const errorResponse = JSON.parse(xhr.responseText);
          if (errorResponse && errorResponse.msg) {
            alert('Error: ' + errorResponse.msg);
          } else {
            alert('Error: Unknown error occurred.');
          }
        } catch (e) {
          alert('Error: Unable to parse the error response.');
          console.error('Error response:', xhr.responseText);
        }
      }
    });
    xhr.send();
  }
  else
  {
    createTower();
    createDisks();
  }

}

setUserPreferences();

document.getElementById('solveButton').addEventListener('click', async () => {
  if(isSolving)
    return;
  cancel = false;
  isSolving = true;
  await solveTowerOfHanoi(numberOfDisks, 0, 1, 2);
  isSolving = false;
});

document.getElementById('resetButton').addEventListener('click', () => {
  resetAnimation();
});

document.getElementById('diskNumber').addEventListener('change', () => {
  updateNumberOfDisks();
});

document.getElementById('animationSpeed').addEventListener('input', () => {
  updateAnimationSpeed();
});