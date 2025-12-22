export const TicTacToe = {
  el: null,
  isGameEnd: false,
  isXTurn: true,

  matrix: [
    [null, null, null],
    [null, null, null],
    [null, null, null],
  ],

  wonCombinations: [
    [[1, 1], [1, 2], [1, 3]],
    [[2, 1], [2, 2], [2, 3]],
    [[3, 1], [3, 2], [3, 3]],
    [[1, 1], [2, 2], [3, 3]],
    [[1, 3], [2, 2], [3, 1]],
    [[1, 1], [2, 1], [3, 1]],
    [[1, 2], [2, 2], [3, 2]],
    [[1, 3], [2, 3], [3, 3]],
  ],

  init({ el, onMove }) {
    this.el = el
    this.onMove = onMove
    this.boxes = el.querySelectorAll('.tic-tac-toe__ceil')
    return this
  },

  initListeners() {
    this.boxes.forEach(box => {
      box.addEventListener('click', event => {
        if (this.isGameEnd || !this.isBlockEmpty(event.target)) return

        this.setBlockValue(event.target)
        this.setBlockDom(event.target)

        if (this.checkForWin()) {
          this.setGameEndStatus()
          setTimeout(() => {
            alert('Победил ' + this.getCurrentTurnValue())
          })
          return
        }

        if (!this.checkHasEmptyBlocks()) {
          this.setGameEndStatus()
          setTimeout(() => {
            alert('Конец игры')
          })
          return
        }

        this.changeTurnValue()
        if (this.onMove) this.onMove(this.isXTurn)
      })
    })
  },

  checkHasEmptyBlocks() {
    for (let r = 0; r < this.matrix.length; r++) {
      for (let c = 0; c < this.matrix[r].length; c++) {
        if (!this.matrix[r][c]) return true
      }
    }
    return false
  },

  startGame() {
    this.initListeners()
    if (this.onMove) this.onMove(this.isXTurn)
  },

  restartGame() {
    this.isGameEnd = false
    this.isXTurn = true

    this.matrix = [
      [null, null, null],
      [null, null, null],
      [null, null, null],
    ]

    this.boxes.forEach(box => {
      this.setBlockDom(box, true)
      this.setBlockValue(box, true)
    })

    if (this.onMove) this.onMove(this.isXTurn)
  },

  isBlockEmpty(target) {
    const [row, col] = this.getBlockPosition(target)
    return !this.matrix[row - 1][col - 1]
  },

  getBlockPosition(target) {
    const { row, col } = target.dataset
    return [row, col]
  },

  setBlockValue(target, clear) {
    const [row, col] = this.getBlockPosition(target)
    const r = row - 1
    const c = col - 1

    if (clear) {
      this.matrix[r][c] = null
    } else {
      this.matrix[r][c] = this.getCurrentTurnValue()
    }
  },

  setBlockDom(target, clear) {
    target.textContent = clear ? '' : this.getCurrentTurnValue()
  },

  getCurrentTurnValue() {
    return this.isXTurn ? 'X' : 'O'
  },

  changeTurnValue() {
    this.isXTurn = !this.isXTurn
  },

  checkForWin() {
    for (let i = 0; i < this.wonCombinations.length; i++) {
      const [a, b, c] = this.wonCombinations[i]

      if (
        this.matrix[a[0] - 1][a[1] - 1] &&
        this.matrix[a[0] - 1][a[1] - 1] === this.matrix[b[0] - 1][b[1] - 1] &&
        this.matrix[b[0] - 1][b[1] - 1] === this.matrix[c[0] - 1][c[1] - 1]
      ) {
        return true
      }
    }
    return false
  },

  setGameEndStatus() {
    this.isGameEnd = true
  }
}